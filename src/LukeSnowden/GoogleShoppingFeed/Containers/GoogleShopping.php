<?php



namespace LukeSnowden\GoogleShoppingFeed\Containers;

use LukeSnowden\GoogleShoppingFeed\Formats;
use LukeSnowden\GoogleShoppingFeed\Formats\Csv;
use LukeSnowden\GoogleShoppingFeed\Formats\Xml;

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
        if (is_null(static::$container) || static::$container == Formats::XML) {
            static::$container = new Xml;
        }

        if (static::$container == Formats::CSV) {
            static::$container = new Csv();
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
