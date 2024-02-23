<?php

namespace kiwi\core\configs;

class ProjectConfig extends Config {

    /**
     * Container経路探索
     */
    public static array $containers;

    /**
     * 適用Containerバージョン情報
     */
    public static array $containerVersions;

    /**
     * Enabling and disabling commander commands.
     */
    public static bool $useCommander;

    /**
     * Enabling and disabling incubator commands
     */    
    public static bool $useIncubator;
}