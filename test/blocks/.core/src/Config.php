<?php

namespace kiwi\core;

class Config {
    /**
     * **Permitted domain list**  
     * Allow access only to the domains specified here.  
     * If not specified, access will be possible from any domain.
     */
    public static array $domains;

    /**
     * **basic authoricate setting**
     */
    public static array $basicAuthority;

    // コンソール実行時パスワードハッシュ
    public static string $consolePasswordHash;
    
    // りクエスト受信時コールバック関数
    public static function before() : void {}
}