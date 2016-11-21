<?php

namespace LukeSnowden\GoogleShoppingFeed\Containers;

use LukeSnowden\GoogleShoppingFeed\Feed;

class GoogleShopping
{
    /**
     * Feed container
     * @var Feed
     */
    public static $container = null;

    /**
     * Return feed container
     * @return Feed
     */
    public static function container()
    {
        if (is_null(static::$container)) {
            static::$container = new Feed;
        }

        return static::$container;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(static::container(), $name), $arguments);
    }
}
