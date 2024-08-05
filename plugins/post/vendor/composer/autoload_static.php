<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaea5408517cc09b58e3ffe386faf7dc9
{
    public static $prefixLengthsPsr4 = array (
        'L' => 
        array (
            'Leo\\Post\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Leo\\Post\\' => 
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
            $loader->prefixLengthsPsr4 = ComposerStaticInitaea5408517cc09b58e3ffe386faf7dc9::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaea5408517cc09b58e3ffe386faf7dc9::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitaea5408517cc09b58e3ffe386faf7dc9::$classMap;

        }, null, ClassLoader::class);
    }
}
