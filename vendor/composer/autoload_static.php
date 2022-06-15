<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc9689e8b9f31b80338283ebb33040043
{
    public static $files = array (
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '2cffec82183ee1cea088009cef9a6fc3' => __DIR__ . '/..' . '/ezyang/htmlpurifier/library/HTMLPurifier.composer.php',
        '091bb482e31030af96936a1127ccdf46' => __DIR__ . '/../..' . '/core/Functions/pa.php',
        '632f4deffadac08b8ceb28a5a5e426e1' => __DIR__ . '/../..' . '/core/Functions/pn.php',
        'ad1daf2aa123e565e53965b3bc86a542' => __DIR__ . '/../..' . '/core/Config.php',
        '7ee6d0bf90921534438b9231d1be51b7' => __DIR__ . '/../..' . '/core/Data.php',
        '22153be273936bbc4a3434947ff718d1' => __DIR__ . '/../..' . '/core/View.php',
        '93da09a2cc7688dda67d8076b828bd92' => __DIR__ . '/../..' . '/core/Registr.php',
        'b650c1d675421a0e33cc39d60e6b50cd' => __DIR__ . '/../..' . '/core/Route.php',
    );

    public static $prefixLengthsPsr4 = array (
        'Z' => 
        array (
            'ZipStream\\' => 10,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Http\\Client\\' => 16,
            'PhpOffice\\PhpSpreadsheet\\' => 25,
        ),
        'M' => 
        array (
            'MyCLabs\\Enum\\' => 13,
            'Matrix\\' => 7,
        ),
        'C' => 
        array (
            'Complex\\' => 8,
        ),
        'A' => 
        array (
            'App\\Views\\' => 10,
            'App\\Models\\' => 11,
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'ZipStream\\' => 
        array (
            0 => __DIR__ . '/..' . '/maennchen/zipstream-php/src',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
            1 => __DIR__ . '/..' . '/psr/http-factory/src',
        ),
        'Psr\\Http\\Client\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-client/src',
        ),
        'PhpOffice\\PhpSpreadsheet\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpoffice/phpspreadsheet/src/PhpSpreadsheet',
        ),
        'MyCLabs\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/myclabs/php-enum/src',
        ),
        'Matrix\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/matrix/classes/src',
        ),
        'Complex\\' => 
        array (
            0 => __DIR__ . '/..' . '/markbaker/complex/classes/src',
        ),
        'App\\Views\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/Views',
        ),
        'App\\Models\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app/Models',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $fallbackDirsPsr4 = array (
        0 => __DIR__ . '/../..' . '/core',
    );

    public static $prefixesPsr0 = array (
        'H' => 
        array (
            'HTMLPurifier' => 
            array (
                0 => __DIR__ . '/..' . '/ezyang/htmlpurifier/library',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc9689e8b9f31b80338283ebb33040043::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc9689e8b9f31b80338283ebb33040043::$prefixDirsPsr4;
            $loader->fallbackDirsPsr4 = ComposerStaticInitc9689e8b9f31b80338283ebb33040043::$fallbackDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitc9689e8b9f31b80338283ebb33040043::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitc9689e8b9f31b80338283ebb33040043::$classMap;

        }, null, ClassLoader::class);
    }
}
