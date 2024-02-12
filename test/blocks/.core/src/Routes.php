<?php

namespace kiwi\core;

use kiwi\AppConfig;
use Exception;

class Routes {

    public static RouteResultWeb $route;

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

                foreach($buffers as $subUrl => $subData) {
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

    public static function route() : void {

        self::getRequest();

        $blocks = AppConfig::$blocks;

        // 使用Blockを決定
        $decisionBlockName = null;
        foreach ($blocks as $url => $blockName) {
            if ($url == "/") {
                $decisionBlockName = $blockName;
            }
            else{
                if (strpos(self::$route -> url . "/", $url . "/") === 0) {
                    $decisionBlockName = $blockName;
                }    
            }
        }

        // 指定BlockのBLockConfigクラスの存在可否を確認
        $blockConfigPath = "kiwi\\" . $decisionBlockName. "\BlockConfig";
        if (!class_exists($blockConfigPath)) {
            throw new Exception("[initial Error] BlockConfig class for specified BLock not found.");
        }

        // BlockConfigクラスのインスタンスを取得
        $bc = new $blockConfigPath();

        // 経路探索リストの正規化
        $bc::$routes = self::routeConverting($bc::$routes);

        self::routeSearch($bc::$routes);

        /*
                    $blockEvent = "kiwi\\test1\\BlockEvent";
            $be = new $blockEvent();
            print(KIWI_ROOTDIR . "/blocks/test1");
            $be->path = KIWI_ROOTDIR . "/blocks/test1";
            $be->begin();
        */
/*

        // 経路探索情報の正規化処理
        Config::$routes = self::routeConverting(Config::$routes);

        foreach ($routes as $url => $data) {
            if (is_array($data)) {

            }
        }
*/
/*
        $result = new RouteResult;
        $result->successed = true;
        $result->block = "main";
        $result->action = "index";
        $result->url = "url...";
        $result->aregments = [];

        Routes::$route = $result;
*/
//        return $result;
    }

    public static function routeSearch(array $routes, string $targetUrl = null) : void {

        if (!$targetUrl) {
            $targetUrl = self::$route -> url;
        }

		$targetUrls = explode("/", $targetUrl);

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
                    print($u_ ."=" . $targetUrls[$ind] . "<br>");

				    if(
                        strpos($u_,"{") > 0 ||
						strpos($u_,"?}") > 0
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
						strpos($urls[$ind],"{") > 0 ||
						strpos($urls[$ind],"?}") > 0
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
	
        echo "<pre>";
        print_r($matrixA);
        print_r($matrixB);
        /*
    	$output = null;

        $confirmPassParams=null;
        foreach($matrixA as $url=>$ma_){
            if($ma_ && $ma_==$matrixB[$url]){

                $output = $routes[$url];

                if(!empty($passParams[$url])){
                    $confirmPassParams = $passParams[$url];
                }
                else{
                    $confirmPassParams = null;
                }
            }
        }

    	$output2 = null;

    	if(is_array($output)){

    //		if($type == self::TYPE_PAGES){

			foreach($output as $method => $o_){
				if($method == "_"){
					$output2 = $o_;
				}
				else{
					if(strtolower($rootParams["method"]) == strtolower($method)){
						$output2 = $o_;
						break;
					}	
				}
			}
		}
        /*
			else if($type == self::TYPE_SHELL){
				$output2 = $output["_"];
			}
        *
		
    	$output2["request"] = $confirmPassParams;
		
        print($output2);
        */
    }

/*
    public static function on($url) : RouteResult {
        $result = new RouteResult;
        $result->successed = true;
        $result->block = "main";
        $result->action = "index";
        $result->url = "url...";
        $result->aregments = [];
        return $result;
    }
*/
    public static function add(string $method, string $controller, string $action = null) : string {
        if ($method) {
            $str = "method:" .$method . ", controller:". $controller;
        }
        else {
            $str = "controller:". $controller. ", action:" . $action;
        }

        if ($action) {
            $str .= ", action:" . $action;
        }

        return $str;
    }

    public static function get(string $controller, string $action = null) : string{
        return self::add("get", $controller, $action);
    }

    public static function post(string $controller, string $action = null) : string{
        return self::add("post", $controller, $action);
    }

}