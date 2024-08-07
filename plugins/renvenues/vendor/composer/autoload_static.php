<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb2add617cb97c07644c2510aa013ad56
{
    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'Huyle\\Renvenues\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Huyle\\Renvenues\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb2add617cb97c07644c2510aa013ad56::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb2add617cb97c07644c2510aa013ad56::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb2add617cb97c07644c2510aa013ad56::$classMap;

        }, null, ClassLoader::class);
    }
}
