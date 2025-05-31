<?php

namespace Nurymbet\Phantom\Cache;

class Setting
{
    private static array $setting = [];

    public function __construct(array $settings)
    {
        static::$setting = $settings;
    }

    public static function get()
    {
        return static::$setting;
    }
}
