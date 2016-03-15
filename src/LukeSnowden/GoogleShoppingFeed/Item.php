<?php namespace LukeSnowden\GoogleShoppingFeed;

use LukeSnowden\GoogleShoppingFeed\Node;
use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

class Item {

	CONST INSTOCK 			= 'in stock';

	CONST OUTOFSTOCK 		= 'out of stock';

	CONST PREORDER 			= 'preorder';

	CONST BRANDNEW 			= 'new';

	CONST USED 				= 'used';

	CONST REFURBISHED 		= 'refurbished';

	CONST MALE 				= 'male';

	CONST FEMALE 			= 'female';

	CONST UNISEX 			= 'unisex';

	CONST NEWBORN 			= 'newborn';

	CONST INFANT 			= 'infant';

	CONST TODDLER 			= 'toddler';

	CONST KIDS 				= 'kids';

	CONST ADULT 			= 'adult';

	CONST EXTRASMALL 		= 'XS';

	CONST SMALL 			= 'S';

	CONST MEDIUM 			= 'M';

	CONST LARGE 			= 'L';

	CONST EXTRALARGE 		= 'XL';

	CONST EXTRAEXTRALARGE 	= 'XXL';

	/**
	 * [$nodes - Stores all of the product nodes]
	 * @var array
	 */
	private $nodes = array();

	/**
	 * [$index description]
	 * @var null
	 */
	private $index = null;

	/**
	 * [$namespace - (g:) namespace definition]
	 * @var string
	 */
	protected $namespace = 'http://base.google.com/ns/1.0';

	/**
	 * [__construct]
	 */
	public function __construct() {}

	/**
	 * [id - Set the ID of the product]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function id( $id ) {
		$node = new Node('id');
		$this->nodes['id'] = $node->value($id)->_namespace($this->namespace);
	}

	/**
	 * [title - Set the title of the product]
	 * @param  [type] $title [description]
	 * @return [type]        [description]
	 */
	public function title( $title ) {
		$node = new Node('title');
		$title = $this->safeCharEncodeText( $title );
		$this->nodes['title'] = $node->value($title)->addCdata();
	}

	/**
	 * [link - Set the link/URL of the product]
	 * @param  [type] $link [description]
	 * @return [type]       [description]
	 */
	public function link( $link ) {
		$node = new Node('link');
		$link = $this->safeCharEncodeURL( $link );
		$this->nodes['link'] = $node->value( $link )->addCdata();
	}

	/**
	 * [price - Set the price of the product, do not format before passing]
	 * @param  [type] $price [description]
	 * @return [type]        [description]
	 */
	public function price( $price ) {
		$node = new Node('price');
		$this->nodes['price'] = $node->value(number_format($price,2,'.',''))->_namespace($this->namespace);
	}

	/**
	 * [sale_price - set the sale price, do not format before passing]
	 * @param  [type] $salePrice [description]
	 * @return [type]            [description]
	 */
	public function sale_price( $salePrice ) {
		$node = new Node('sale_price');
		$this->nodes['sale_price'] = $node->value(number_format($salePrice,2,'.',''))->_namespace($this->namespace);
	}

	/**
	 * [description - Set the description of the product]
	 * @param  [type] $description [description]
	 * @return [type]              [description]
	 */
	public function description( $description ) {
		$node = new Node('description');
		$description = $this->safeCharEncodeText( $description );
		$this->nodes['description'] = $node->value(substr($description,0,5000))->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [condition - Set the condition of the product (pass in the constants above to standardise the values)]
	 * @param  [type] $condition [description]
	 * @return [type]            [description]
	 */
	public function condition( $condition ) {
		$node = new Node('condition');
		$this->nodes['condition'] = $node->value($condition)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [expiration_date description]
	 * @param  [type] $expirationDate [description]
	 * @return [type]                 [description]
	 */
	public function expiration_date( $expirationDate ) {
		$node = new Node('expiration_date');
		$this->nodes['expiration_date'] = $node->value($expirationDate)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [image_link description]
	 * @param  [type] $imageLink [description]
	 * @return [type]            [description]
	 */
	public function image_link( $imageLink ) {
		$node = new Node('image_link');
		$imageLink = $this->safeCharEncodeURL( $imageLink );
		$this->nodes['image_link'] = $node->value( $imageLink )->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [brand description]
	 * @param  [type] $brand [description]
	 * @return [type]        [description]
	 */
	public function brand( $brand ) {
		$node = new Node('brand');
		$brand = $this->safeCharEncodeText( $brand );
		$this->nodes['brand'] = $node->value($brand)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [mpn description]
	 * @param  [type] $mnp [description]
	 * @return [type]      [description]
	 */
	public function mpn( $mpn ) {
		$node = new Node('mpn');
		$this->nodes['mpn'] = $node->value($mpn)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [gtin description]
	 * @param  [type] $gtin [description]
	 * @return [type]       [description]
	 */
	public function gtin( $gtin ) {
		$node = new Node('gtin');
		$this->nodes['gtin'] = $node->value($gtin)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [product_type description]
	 * @param  [type] $productType [description]
	 * @return [type]              [description]
	 */
	public function product_type( $productType ) {
		$node = new Node('product_type');
		$brand = $this->safeCharEncodeText( $productType );
		$this->nodes['product_type'] = $node->value($productType)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [google_product_category description]
	 * @param  [type] $googleProductCategory [description]
	 * @return [type]                        [description]
	 */
	public function google_product_category( $googleProductCategory ) {
		$node = new Node('google_product_category');
		$this->nodes['google_product_category'] = $node->value($googleProductCategory)->_namespace($this->namespace)->addCdata();
	}

	/**
	 * [availability description]
	 * @param  [type] $availability [description]
	 * @return [type]               [description]
	 */
	public function availability( $availability ) {
		$node = new Node('availability');
		$this->nodes['availability'] = $node->value($availability)->_namespace($this->namespace);
	}

	/**
	 * [shipping description]
	 * @param  [type] $code    [description]
	 * @param  [type] $service [description]
	 * @param  [type] $cost    [description]
	 * @return [type]          [description]
	 */
	public function shipping( $code, $service, $cost ) {
		$node = new Node('shipping');
		$value = "<g:country>{$code}</g:country><g:service>{$service}</g:service><g:price>{$cost}</g:price>";
		if( ! isset( $this->nodes['shipping'] ) ) {
			$this->nodes['shipping'] = array();
		}
		$this->nodes['shipping'][] = $node->value($value)->_namespace($this->namespace);
	}

	/**
	 * [size description]
	 * @param  [type] $size [description]
	 * @return [type]       [description]
	 */
	public function size( $size ) {
		$node = new Node('size');
		$this->nodes['size'] = $node->value($size)->_namespace($this->namespace);
	}

	/**
	 * [gender description]
	 * @param  [type] $gender [description]
	 * @return [type]         [description]
	 */
	public function gender( $gender ) {
		$node = new Node('gender');
		$this->nodes['gender'] = $node->value($gender)->_namespace($this->namespace);
	}

	/**
	 * [age_group description]
	 * @param  [type] $ageGroup [description]
	 * @return [type]           [description]
	 */
	public function age_group( $ageGroup ) {
		$node = new Node('age_group');
		$this->nodes['age_group'] = $node->value($ageGroup)->_namespace($this->namespace);
	}

	/**
	 * [color description]
	 * @param  [type] $color [description]
	 * @return [type]        [description]
	 */
	public function color( $color ) {
		$node = new Node('color');
		$this->nodes['color'] = $node->value($color)->_namespace($this->namespace);
	}

	/**
	 * [item_group_id description]
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */

	public function item_group_id( $id ) {
		$node = new Node('item_group_id');
		$this->nodes['item_group_id'] = $node->value($id)->_namespace($this->namespace);
	}

	/**
	 * [nodes description]
	 * @return [type] [description]
	 */
	public function nodes() {
		return $this->nodes;
	}

	/**
	 * [setIndex description]
	 * @param [type] $index [description]
	 */
	public function setIndex( $index ) {
		$this->index = $index;
	}

	/**
	 * [delete description]
	 * @return [type] [description]
	 */
	public function delete() {
		GoogleShopping::removeItemByIndex( $this->index );
	}

	/**
	 * [clone description]
	 * @return [type] [description]
	 */
	public function cloneIt() {
		$item = GoogleShopping::createItem();
		$this->item_group_id( $this->nodes['mpn']->get('value') . '_group' );
		foreach( $this->nodes as $node ) {
			if( is_array( $node ) ) {
				// multiple accepted values..
				$name = $node[0]->get('name');
				foreach( $node as $_node ) {
					if( $name == 'shipping' ) {
						// Shipping has another layer so we are going to have to do a little hack
						$xml = simplexml_load_string( '<foo>' . trim( str_replace( 'g:', '', $_node->get('value') ) ) . '</foo>' );
						$item->{$_node->get('name')}( $xml->country, $xml->service, $xml->price );
					} else {
						$item->{$name}( $_node->get('value') );
					}
				}

			} else if( $node->get('name') !== 'shipping' ) {
				$item->{$node->get('name')}( $node->get('value') );
			}
		}
		return $item;
	}

	/**
	 * [variant description]
	 * @return [type] [description]
	 */
	public function variant() {
		$item = $this->cloneIt();
		$item->item_group_id( $this->nodes['mpn']->get('value') . '_group' );
		return $item;
	}

	/**
	 * [safeCharEncode description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	private function safeCharEncodeURL( $string ) {
		return str_replace(
			array('%','[',']','{','}','|',' ','"','<','>','#','\\','^','~','`'),
			array('%25','%5b','%5d','%7b','%7d','%7c','%20','%22','%3c','%3e','%23','%5c','%5e','%7e','%60'),
		$string );
	}

	/**
	 * [safeCharEncodeText description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	private function safeCharEncodeText( $string ) {
		return str_replace(
			array('•','”','“','’','‘','™','®','°'),
			array('&#8226;','&#8221;','&#8220;','&#8217;','&#8216;','&trade;','&reg;','&deg;'),
		$string );
	}

}