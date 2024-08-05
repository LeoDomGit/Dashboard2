<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit30ea5f577de9165a54096179f57a98f5
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Leo\\Roles\\Providers\\' => 20,
            'Leo\\Roles\\Database\\Seeders\\' => 27,
            'Leo\\Roles\\Database\\Migrations\\' => 30,
            'Leo\\Roles\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Leo\\Roles\\Providers\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Providers',
        ),
        'Leo\\Roles\\Database\\Seeders\\' => 
        array (
            0 => __DIR__ . '/../..' . '/database/seeders',
        ),
        'Leo\\Roles\\Database\\Migrations\\' => 
        array (
            0 => __DIR__ . '/../..' . '/database/migrations',
        ),
        'Leo\\Roles\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Leo\\Role\\Providers\\RolesServiceProvider' => __DIR__ . '/../..' . '/src/Providers/RolesServiceProvider.php',
        'Leo\\Roles\\Controllers\\RolesController' => __DIR__ . '/../..' . '/src/Controllers/RolesController.php',
        'Leo\\Roles\\Models\\Roles' => __DIR__ . '/../..' . '/src/Models/Roles.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit30ea5f577de9165a54096179f57a98f5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit30ea5f577de9165a54096179f57a98f5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit30ea5f577de9165a54096179f57a98f5::$classMap;

        }, null, ClassLoader::class);
    }
}
