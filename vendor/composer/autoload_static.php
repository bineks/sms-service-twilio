<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit8cfcb103933c848ce097a115611007cc
{
    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'Twilio\\' => 7,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Twilio\\' => 
        array (
            0 => __DIR__ . '/..' . '/twilio/sdk/Twilio',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit8cfcb103933c848ce097a115611007cc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit8cfcb103933c848ce097a115611007cc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
