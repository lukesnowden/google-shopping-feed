<?php namespace LukeSnowden\GoogleShoppingFeed;

use SimpleXMLElement;
use LukeSnowden\GoogleShoppingFeed\Item;
use Gregwar\Cache\Cache;

class Feed {

	/**
	 * [$namespace description]
	 * @var string
	 */
	protected $namespace = 'http://base.google.com/ns/1.0';

	/**
	 * [$version description]
	 * @var string
	 */
	protected $version = '2.0';

	/**
	 * [$items Stores the list of items for the feed]
	 * @var array
	 */
	private $items = array();

	/**
	 * [$channelCreated description]
	 * @var boolean
	 */
	private $channelCreated = false;

	/**
	 * [$feed The base for the feed]
	 * @var null
	 */
	private $feed = null;

	/**
	 * [$title description]
	 * @var string
	 */
	private $title = '';

	/**
	 * [$cacheDir description]
	 * @var string
	 */
	private $cacheDir = 'cache';

	/**
	 * [$description description]
	 * @var string
	 */
	private $description = '';

	/**
	 * [$link description]
	 * @var string
	 */
	private $link = '';

	/**
	 * [__construct description]
	 */
	public function __construct() {
		$this->feed = new SimpleXMLElement( '<rss xmlns:g="' . $this->namespace . '" version="' . $this->version . '"></rss>' );
	}

	/**
	 * [title description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function title( $string ) {
		$this->title = (string)$string;
	}

	/**
	 * [description description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function description( $string ) {
		$this->description = (string)$string;
	}

	/**
	 * [link description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	public function link( $string ) {
		$this->link = (string)$string;
	}

	/**
	 * [channel description]
	 * @return [type] [description]
	 */
	private function channel() {
		if( ! $this->channelCreated ) {
			$channel = $this->feed->addChild('channel');
			$channel->addChild( 'title', $this->title );
	        $channel->addChild( 'link', $this->link );
	        $channel->addChild( 'description', $this->description );
	        $this->channelCreated = true;
	    }
	}

	/**
	 * [createItem description]
	 * @return [type] [description]
	 */
	public function createItem() {
		$this->channel();
		$item = new Item;
		$this->items[] = $item;
		return $item;
	}

	/**
	 * [standardiseSizeVarient description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function standardiseSizeVarient( $value ) {
		return $value;
	}

	/**
	 * [standardiseSizeVarient description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function standardiseColourVarient( $value ) {
		return $value;
	}

	/**
	 * [isVariant description]
	 * @param  [type]  $group [description]
	 * @return boolean        [description]
	 */
	public function isVariant( $group ) {
		if( preg_match( "#^\s*colou?rs?\s*$#is", trim( $group ) ) ) return 'color';
		if( preg_match( "#^\s*sizes?\s*$#is", trim( $group ) ) ) return 'size';
		if( preg_match( "#^\s*materials?\s*$#is", trim( $group ) ) ) return 'material';
		return false;
	}

	/**
	 * [addItemsToFeed description]
	 */
	private function addItemsToFeed() {
		foreach( $this->items as $item ) {
			$feedItemNode = $this->feed->channel->addChild('item');
			foreach( $item->nodes() as $itemNode ) {
				if( is_array( $itemNode ) ) {
					foreach( $itemNode as $node ) {
						$feedItemNode->addChild( $node->get('name'), $node->get('value'), $node->get('_namespace'));
					}
				} else {
					$itemNode->attachNodeTo( $feedItemNode );
				}
			}
		}
	}

	/**
	 * [categories description]
	 * @param  [type] $selected [description]
	 * @return [type]           [description]
	 */
	public function categories() {
		$cache = new Cache;
		$cache->setCacheDirectory($this->cacheDir);
		$data = $cache->getOrCreate('google-feed-taxonomy.txt', array( 'max-age' => '860400' ), function() {
		    return file_get_contents("http://www.google.com/basepages/producttype/taxonomy.en-GB.txt");
		});
		return explode( "\n", trim($data) );
	}

	/**
	 * [categoriesAsSelect description]
	 * @param  string $selected [description]
	 * @return [type]           [description]
	 */
	public function categoriesAsSelect( $selected = '' ) {
		$categories = $this->categories();
		unset($categories[0]);
		$select = '<select name="google_category">';
		$select .= '<option value="">Please select a Google Category</option>';
		foreach( $categories as $category ) {
			$select .= '<option ' . ( $category == $selected ? 'selected' : '' ) . ' name="' . $category . '">' . $category . '</option>';
		}
		$select .= '</select>';
		return $select;
	}

	/**
	 * [asRss description]
	 * @return [type] [description]
	 */
	public function asRss( $output = false ) {
		ob_end_clean();
		$this->addItemsToFeed();
		$data = html_entity_decode( $this->feed->asXml() );
		if( $output ) {
			header('Content-Type: application/xml; charset=utf-8');
			die( $data );
		}
		return $data;
	}

	/**
	 * [removeLastItem description]
	 * @return [type] [description]
	 */
	public function removeLastItem(){
		array_pop( $this->items );
	}

}