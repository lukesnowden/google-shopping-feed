<?php
/**
 * Created by PhpStorm.
 * User: szymon
 * Date: 3/15/19
 * Time: 4:03 AM
 */

namespace LukeSnowden\GoogleShoppingFeed;


abstract class FeedAbstract implements FeedInterface
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
    protected $items = array();

    /**
     * @var bool
     */
    protected $channelCreated = false;

    /**
     * The base for the feed
     * @var SimpleXMLElement
     */
    protected $feed = null;

    /**
     * @var string
     */
    protected $title = '';

    /**
     * @var string
     */
    protected $cacheDir = 'cache';

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var string
     */
    protected $link = '';



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

    public function removeItemByIndex($index){
        if(array_key_exists($index, $this->items)) {
            unset($this->items[$index]);
        }
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
     * Remove last inserted item
     */
    public function removeLastItem()
    {
        array_pop($this->items);
    }

    public function createItem()
    {
        // TODO: Implement createItem() method.
    }

    public function asRss($output = false)
    {
        // TODO: Implement asRss() method.
    }


}