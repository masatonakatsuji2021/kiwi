<?php

namespace kiwi\core;

use kiwi\Config;
use Exception;

class Routes {

    public static RouteResultWeb $route;

    private static function getConsole() : void {
        print_r("console .....");
        exit;
    }

    private static function getRequest() : void {
        $res = new RouteResultWeb;

        $cutUrl = dirname($_SERVER["PHP_SELF"], KIWI_ROOTUNDERCUT + 1);
        $requestUrl = substr($_SERVER["REQUEST_URI"], strlen($cutUrl));
        $requestUrl = explode("?", $requestUrl)[0];

        $ip = $_SERVER["REMOTE_ADDR"];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        $res -> domain = $_SERVER["HTTP_HOST"];
        $res -> full = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
        $res -> ip = $ip;
        $res -> url = $requestUrl;
        $res -> method = $_SERVER["REQUEST_METHOD"];

        self::$route = $res;
    }

    // 経路探索情報の正規化処理
    private static function routeConverting(array $routeData) : array {

        $res = [];

        foreach ($routeData as $url => $data) {
            if (is_array($data)) {
                // データが配列値or連想配列の場合

                // さらに正規化
                $buffers = self::routeConverting($data);

                foreach ($buffers as $subUrl => $subData) {
                    $url2 = $url;
                    if ($subUrl != "/") {
                        $url2 .= $subUrl;
                    } 
                    $res[$url2] = $subData;
                }
            }
            else {
                // データが文字列の場合
                $res[$url] = $data;
            }
        }

        return $res;
    }

    private static function getParam(array $params, string $header, string $default = null) : ?string {
        $res = $default;
        foreach ($params as $p_) {
            if (strpos(trim($p_), $header . ":") > -1) {
                $res = substr(trim($p_), strlen($header . ":"));
            }
        }
        return $res;
    }

    // 使用Blockの検索
    private static function searchBlock(array $blocks) : ?string {
        $res = null;
        foreach ($blocks as $url => $blockName) {
            if ($url == "/") {
                $res = $blockName;
            }
            else{
                if (strpos(self::$route -> url . "/", $url . "/") === 0) {
                    $res = $blockName;
                }    
            }
        }

        return $res;
    }

    public static function route(bool $consoleMode) : void {

        if ($consoleMode) {
            self::getConsole();
        }
        else {
            self::getRequest();
        }

        // blockの検索
        $decisionBlockName = self::searchBlock(Config::$blocks);

        if (!$decisionBlockName) {
            // Blockがnullの場合はエラー
            throw new Exception("[Error] Block Not Found.");
        }

        self::$route -> block = $decisionBlockName;
        self::$route -> blockPath = KIWI_ROOTDIR . "/blocks/" . $decisionBlockName;

        // 指定BlockのBLockConfigクラスの存在可否を確認
        $blockConfigPath = "kiwi\\" . $decisionBlockName. "\BlockConfig";
        if (!class_exists($blockConfigPath)) {
            throw new Exception("[initial Error] BlockConfig class for specified BLock not found.");
        }

        // BlockConfigクラスのインスタンスを取得
        $bc = new $blockConfigPath();

        // 経路探索のイベントハンドラ
        $bc::handleRoute();

        // 経路探索リストの正規化
        $bc::$routes = self::routeConverting($bc::$routes);

        // 経路探索リストから結果を抽出
        self::routeSearch($bc::$routes);
    }

    public static function routeSearch(array $routes, string $targetUrl = null) : void {

        if (!$targetUrl) {
            $targetUrl = self::$route -> url;
        }

		$targetUrls = explode("/", $targetUrl);
        if (!end($targetUrls)) {
			array_pop($targetUrls);
		}
		array_shift($targetUrls);

		$passParams = [];
		$matrixA = [];
		$matrixB = [];
		foreach($routes as $url => $route){

            $url0 = str_replace("*", "{?}/{?}/{?}/{?}/{?}/{?}/{?}/{?}/{?}/{?}/{?}", $url);

            $urls = explode("/", $url0);
            if(!end($urls)){
                array_pop($urls);
            }
			array_shift($urls);
            
			$jugeA = true;
			foreach ($urls as $ind => $u_) {
				if (empty($targetUrls[$ind])) {
					$targetUrls[$ind] = "";
				}

				if ($u_ !== $targetUrls[$ind]) {
				    if(
                        strpos($u_,"{?") > 0 ||
						strpos($u_,"}") > 0
					){
						if ($targetUrls[$ind]) {
							if (empty($passParams[$url])) {
								$passParams[$url] = [];
							}
							$passParams[$url][] = $targetUrls[$ind];
						}
					}
					else if (
						strpos($u_,"{") > 0 ||
						strpos($u_,"}") > 0
					) {
						if($targetUrls[$ind]){
							if (empty($passParams[$url])) {
								$passParams[$url] = [];
							}
							$passParams[$url][] = $targetUrls[$ind];
						}

                        if (!$targetUrls[$ind]) {
							$jugeA = false;
						}
					}
					else{
						$jugeA = false;
					}
				}
			}

			$jugeB = true;
			foreach ($targetUrls as $ind => $r_) {
				if (empty($urls[$ind])) {
					$urls[$ind] = "";
	    		}
				if ($urls[$ind] !== $r_) {
					if(
						strpos($urls[$ind],"{?") > 0 ||
						strpos($urls[$ind],"}") > 0
					){

					}
					else if (
						strpos($urls[$ind],"{") > 0 ||
						strpos($urls[$ind],"}") > 0
					){
						if (!$r_) {
							$jugeB = false;
						}
					}
					else {
						$jugeB = false;
					}
				}
			}

		    $matrixA[$url] = $jugeA;
			$matrixB[$url] = $jugeB;

		}
	
    	$decision = null;

        $confirmPassParams=null;
        foreach($matrixA as $url=>$ma_){
            if($ma_ && $ma_==$matrixB[$url]){

                $decision = $routes[$url];

                if(!empty($passParams[$url])){
                    $confirmPassParams = $passParams[$url];
                }
                else{
                    $confirmPassParams = null;
                }
            }
        }

        $decisions = explode(",", $decision);
        $controller = self::getParam($decisions, "controller");
        $action = self::getParam($decisions, "action", "index");
        $method = self::getParam($decisions, "method", "_");

        if ($controller) {
            self::$route -> successed = true;
            self::$route -> controller = $controller;
            self::$route -> action = $action;
            self::$route -> method = $method;    
            self::$route -> aregments = $confirmPassParams;
        }
        else {
            self::$route -> successed = false;
        }
    }
}