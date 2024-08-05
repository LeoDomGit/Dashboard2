<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit5576776d84999f2bc3f185f7ce2d4b58
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInit5576776d84999f2bc3f185f7ce2d4b58', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit5576776d84999f2bc3f185f7ce2d4b58', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit5576776d84999f2bc3f185f7ce2d4b58::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}