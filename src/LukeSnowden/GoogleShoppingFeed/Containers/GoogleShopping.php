<?php namespace LukeSnowden\GoogleShoppingFeed\Containers;

use LukeSnowden\GoogleShoppingFeed\Feed;

class GoogleShopping
{

    /**
     * [$containers description]
     * @var array
     */

    public static $container = null;

    /**
     * [container description]
     * @return [type] [description]
     */

    public static function container()
    {
        if (is_null(static::$container)) {
            static::$container = new Feed;
        }

        return static::$container;
    }

    /**
     * [__callStatic description]
     * @param  [type] $name      [description]
     * @param  [type] $arguments [description]
     * @return [type]            [description]
     */

    public static function __callStatic($name, $arguments)
    {
        return call_user_func_array(array(static::container(), $name), $arguments);
    }
}
