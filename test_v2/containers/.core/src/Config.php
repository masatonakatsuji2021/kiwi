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

    /**
     * Enabling and disabling commander commands.
     */
    public static bool $useCommander;

    /**
     * Enabling and disabling incubator commands
     */    
    public static bool $useIncubator;

    /**
     * Callback function when receiving a request
     */
    public static function handleRequest() : void {}
}