<?php
/**
 * Created by PhpStorm.
 * User: szymon
 * Date: 3/14/19
 * Time: 8:26 AM
 */

namespace LukeSnowden\GoogleShoppingFeed;

interface FeedInterface
{
    /**
     * @param string $title
     */
    public function title($title);

    /**
     * @param string $description
     */
    public function description($description);

    /**
     * @param string $link
     */
    public function link($link);

    /**
     * @param $code
     */
    public function setIso4217CountryCode($code);

    /**
     * @return string
     */
    public function getIso4217CountryCode();

    /**
     * @return Item
     */
    public function createItem();

    /**
     * @param int $index
     */
    public function removeItemByIndex($index);

    /**
     * @param string $group
     * @return bool|string
     */
    public function isVariant($group);

    /**
     * Retrieve Google product categories from internet and cache the result
     * @param string $languageISO639
     * @return array
     */
    public function categories($languageISO639 = 'gb');

    /**
     * Build an HTML select containing Google taxonomy categories
     * @param string $selected
     * @param string $languageISO639
     * @return string
     */
    public function categoriesAsSelect($selected = '', $languageISO639 = 'gb');

    /**
     * @param string $languageISO639
     * @return array
     */
    public function categoriesAsNameAssociativeArray($languageISO639 = 'gb');

    /**
     * Generate RSS feed
     * @param bool $output
     * @return string
     */
    public function asRss($output = false);

    /**
     * Remove last inserted item
     */
    public function removeLastItem();
}