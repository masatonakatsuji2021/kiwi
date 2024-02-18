<?php

namespace kiwi\core;

class Container {

    // ContainerConfigクラス取得
    public static function getConfig(string $containerName) : ?ContainerConfig {

        $container = "\kiwi\\" . $containerName . "\app\\ContainerConfig";
                
        if (!class_exists($container)) {
            return null;
        }

        return new $container;
    }
    
    // インストール済Containerリスト取得
    public static function locals(array $options = null) : array {
        return [];  
    }
    
    // 指定Container名のインストール済Container取得
    public static function local(string $ContainerName) : ?LocalContainer{
        return null;
    }
    
    // 指定リポジトリ情報での最新版RemoteContainer取得
    public static function remote(ContainerRepository $containerRepository) : ?RemoteContainer {
        return null;
    }
    
    // 指定Containerのバージョンデータリスト取得
    public static function versions(string $containerName) : ?array {
        return [];
    }

    // 指定Container, バージョン番号のバージョンデータContainerの取得
    public static function getVersion(string $ContainerName, string $version) : ?VersionContainer {
        return null;
    }

    // 指定Containerファイルをバージョンデータへセット
    public static function setVersion(string $containerName, string $containerFilePath) : bool {
        return true;
    }    
}

