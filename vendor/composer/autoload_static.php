<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitba06ef4404b5a370843768d7945bec49
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Parsedown' => 
            array (
                0 => __DIR__ . '/..' . '/erusev/parsedown',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitba06ef4404b5a370843768d7945bec49::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitba06ef4404b5a370843768d7945bec49::$classMap;

        }, null, ClassLoader::class);
    }
}
