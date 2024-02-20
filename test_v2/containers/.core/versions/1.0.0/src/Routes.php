<?php
/**
 * MIT License
 *
 * Copyright (c) 2024 Masato Nakatsuji
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace kiwi\core;

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

    private static function routeResource(ContainerConfig $cc) : bool {
        if (!isset($cc::$resources)) {
            return false;
        }
        return self::routeResourceWritable("resources", $cc::$resources);
    }

    private static function routeWritable(ContainerConfig $cc) : bool {
        if (!isset($cc::$writables)) {
            return false;
        }
        return self::routeResourceWritable("writables", $cc::$writables);
    }

    private static function routeResourceWritable(string $type, array $routes) : bool {

        $decision = null;
        foreach ($routes as $url => $r_) {
            $targetUrl = "/" . self::$route -> container . $url;
            if (strpos(self::$route -> url, $targetUrl) === 0) {
                $decision = $r_;
            }
        }

        if (!$decision) {
            return false;
        }

        if (!$decision["release"]) {
            return false;
        }

        $path = str_replace("//", "/", KIWI_ROOT_CONTAINER . "/" . self::$route -> container . "/versions/" . self::$route -> containerVersion . "/" . $type . "/" . substr(self::$route -> url, strlen("/". self::$route -> container)));

        if (!file_exists($path)) {
            return false;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($path);

        header("Content-Type: " . $mimeType);
        if (isset($decision["cache-max-age"])) {
            header("Cache-Control: max-age=" . $decision["cache-max-age"]);
        }

        echo file_get_contents($path);

        return true;
    }

    public static function routeWeb() : bool {

        self::getRequest();

        $kiwiJson = kiwiJsonLoad();

        // containerの検索
        $decisionContainer = null;
        $changeUrl = null;
        $addUrl = null;
        foreach ((array)$kiwiJson["routes"] as $url => $r_) {
            if (strpos(self::$route -> url, $url) === 0) {
                $decisionContainer = $r_["container"];
                $changeUrl = $url;
                if (isset($r_["route"])) {
                    $addUrl = $r_["route"];
                }
            }
        }

        if (!$decisionContainer) {
            // decisionContainer がnullの場合はエラー
            throw new Exception("[Error] Container is Not Found.");
        }

        self::$route -> container = $decisionContainer;
        self::$route -> containerPath = KIWI_ROOT_CONTAINER . "/". $decisionContainer;
        self::$route -> containerVersion = $kiwiJson["versions"][$decisionContainer];
        self::$route -> url = substr(self::$route -> url, strlen($changeUrl));
        if (!$addUrl) {
            $addUrl = "/";
        }
        self::$route -> url = $addUrl . self::$route -> url;
        self::$route -> url = str_replace("//", "/", self::$route -> url);

        // 指定ContainerのContainerConfigクラスの存在を確認
        $cc = Container::getConfig($decisionContainer);
        if(!$cc){
            // ContainerConfigクラスがなければエラー
            throw new Exception("[initial Error] ContainerConfig class for specified Container not found.");
        }

        // resource data
        $juge = self::routeResource($cc);
        if ($juge){
            return false;
        }

        // writable data
        $juge = Routes::routeWritable($cc);
        if ($juge) {
            return false;
        }

        // 経路探索のイベントハンドラ
        $buff = $cc::handleRoute($cc::$routes);
        if ($buff) {
            $cc::$routes = $buff;
        }

        // 経路探索リストの正規化
        $cc::$routes = self::routeConverting($cc::$routes);

        // 経路探索リストから結果を抽出
        self::routeSearch($cc::$routes);

        return true;
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