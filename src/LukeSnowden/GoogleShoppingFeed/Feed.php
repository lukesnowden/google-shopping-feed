<?php

namespace LukeSnowden\GoogleShoppingFeed;

use SimpleXMLElement;
use LukeSnowden\GoogleShoppingFeed\Item;
use Gregwar\Cache\Cache;

class Feed
{

    /**
     * Define Google Namespace url
     * @var string
     */
    protected $namespace = 'http://base.google.com/ns/1.0';

    /**
     * @var string
     */
    protected $version = '2.0';

    /**
     * @var string
     */
    protected $iso4217CountryCode = 'GBP';

    /**
     * Stores the list of items for the feed
     * @var Item[]
     */
    private $items = array();

    /**
     * @var bool
     */
    private $channelCreated = false;

    /**
     * The base for the feed
     * @var SimpleXMLElement
     */
    private $feed = null;

    /**
     * @var string
     */
    private $title = '';

    /**
     * @var string
     */
    private $cacheDir = 'cache';

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var string
     */
    private $link = '';

    /**
     * Feed constructor
     */
    public function __construct()
    {
        $document = '<?xml version="1.0" encoding="utf-8"?>';
        $document =  $document . '<rss xmlns:g="' . $this->namespace . '" version="' . $this->version . '"></rss>';

        $this->feed = new SimpleXMLElement($document);
    }

    /**
     * @param string $title
     */
    public function title($title)
    {
        $this->title = (string)$title;
    }

    /**
     * @param string $description
     */
    public function description($description)
    {
        $this->description = (string)$description;
    }

    /**
     * @param string $link
     */
    public function link($link)
    {
        $this->link = (string)$link;
    }

    /**
     * @param $code
     */
    public function setIso4217CountryCode( $code )
    {
        $this->iso4217CountryCode = $code;
    }

    /**
     * @return string
     */
    public function getIso4217CountryCode()
    {
        return $this->iso4217CountryCode;
    }

    /**
     * [channel description]
     */
    private function channel()
    {
        if (! $this->channelCreated) {
            $channel = $this->feed->addChild('channel');
            $channel->addChild('title', htmlspecialchars($this->title));
            $channel->addChild('link', htmlspecialchars($this->link));
            $channel->addChild('description', htmlspecialchars($this->description));
            $this->channelCreated = true;
        }
    }

    /**
     * @return Item
     */
    public function createItem()
    {
        $this->channel();
        $item = new Item($this);
        $index = 'index_' . md5(microtime());
        $this->items[$index] = $item;
        $item->setIndex($index);
        return $item;
    }

    /**
     * @param int $index
     */
    public function removeItemByIndex($index)
    {
        unset($this->items[$index]);
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function standardiseSizeVarient($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function standardiseColourVarient($value)
    {
        return $value;
    }

    /**
     * @param string $group
     * @return bool|string
     */
    public function isVariant($group)
    {
        if (preg_match("#^\s*colou?rs?\s*$#is", trim($group))) {
            return 'color';
        }
        if (preg_match("#^\s*sizes?\s*$#is", trim($group))) {
            return 'size';
        }
        if (preg_match("#^\s*materials?\s*$#is", trim($group))) {
            return 'material';
        }
        return false;
    }

    /**
     * Adds items to feed
     */
    private function addItemsToFeed()
    {
        foreach ($this->items as $item) {
            /** @var SimpleXMLElement $feedItemNode */
            $feedItemNode = $this->feed->channel->addChild('item');
            foreach ($item->nodes() as $itemNode) {
                if (is_array($itemNode)) {
                    foreach ($itemNode as $node) {
                        $feedItemNode->addChild($node->get('name'), $node->get('value'), $node->get('_namespace'));
                    }
                } else {
                    $itemNode->attachNodeTo($feedItemNode);
                }
            }
        }
    }

    /**
     * Retrieve Google product categories from internet and cache the result
     * @param string $languageISO639
     * @return array
     */
    public function categories($languageISO639 = 'gb')
    {
        //map two letter language to culture
        $languageMap = array(
            'au' => 'en-AU',
            'br' => 'pt-BR',
            'cn' => 'zh-CN',
            'cz' => 'cs-CZ',
            'de' => 'de-DE',
            'dk' => 'da-DK',
            'es' => 'es-ES',
            'fr' => 'fr-FR',
            'gb' => 'en-GB',
            'it' => 'it-IT',
            'jp' => 'ja-JP',
            'nl' => 'nl-NL',
            'no' => 'no-NO',
            'pl' => 'pl-PL',
            'ru' => 'ru-RU',
            'sw' => 'sv-SE',
            'tr' => 'tr-TR',
            'us' => 'en-US'
        );
        //set default language to gb for backward compatibility
        $languageCulture = $languageMap['gb'];
        if (array_key_exists($languageISO639, $languageMap)) {
            $languageCulture = $languageMap[$languageISO639];
        }

        $cache = new Cache;
        $cache->setCacheDirectory($this->cacheDir);
        $data = $cache->getOrCreate('google-feed-taxonomy.'.$languageISO639.'.txt', array('max-age' => '86400'),
            function () use ($languageCulture) {
                return file_get_contents("http://www.google.com/basepages/producttype/taxonomy." . $languageCulture . ".txt");
            }
        );

        return explode("\n", trim($data));
    }

    /**
     * Build an HTML select containing Google taxonomy categories
     * @param string $selected
     * @param string $languageISO639
     * @return string
     */
    public function categoriesAsSelect($selected = '', $languageISO639 = 'gb')
    {
        $categories = $this->categories($languageISO639);
        unset($categories[0]);
        $select = '<select name="google_category">';
        $select .= '<option value="">Please select a Google Category</option>';
        foreach ($categories as $category) {
            $select .= '<option ' . ($category == $selected ? 'selected' : '') . ' name="' . $category . '">' . $category . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    /**
     * @param string $languageISO639
     * @return array
     */
    public function categoriesAsNameAssociativeArray( $languageISO639 = 'gb' )
    {
        $categories = $this->categories($languageISO639);
        unset($categories[0]);
        $return = [];
        foreach( $categories as $key => $value ) {
            $return[$value] = $value;
        }
        return $return;
    }

    /**
     * Generate RSS feed
     * @param bool $output
     * @return string
     */
    public function asRss($output = false)
    {
        if (ob_get_contents()) ob_end_clean();
        $this->addItemsToFeed();
        $data = html_entity_decode($this->feed->asXml());
        if ($output) {
            header('Content-Type: application/xml; charset=utf-8');
            die($data);
        }
        return $data;
    }

    /**
     * Remove last inserted item
     */
    public function removeLastItem()
    {
        array_pop($this->items);
    }
}
