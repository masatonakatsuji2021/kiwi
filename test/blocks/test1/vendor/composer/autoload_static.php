<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94245e169dd539f60786cf18565e7fa2
{
    public static $prefixLengthsPsr4 = array (
        'V' => 
        array (
            'Valitron\\' => 9,
        ),
        'P' => 
        array (
            'Picqer\\Barcode\\' => 15,
        ),
        'G' => 
        array (
            'Gmu\\' => 4,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Valitron\\' => 
        array (
            0 => __DIR__ . '/..' . '/vlucas/valitron/src/Valitron',
        ),
        'Picqer\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/picqer/php-barcode-generator/src',
        ),
        'Gmu\\' => 
        array (
            0 => __DIR__ . '/../..' . '/lib/Gmu',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit94245e169dd539f60786cf18565e7fa2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit94245e169dd539f60786cf18565e7fa2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit94245e169dd539f60786cf18565e7fa2::$classMap;

        }, null, ClassLoader::class);
    }
}
