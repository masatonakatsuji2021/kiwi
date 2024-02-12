<?php

namespace kiwi\core;

class AppConfig extends Config{

    /**
     * **block routing settings**  
     * Specify route search list for each block.
     */
    public array $blocks;

    // Makeコマンド実行時パスワードハッシュ
    public static string $makeCmdPasswordHash;
}