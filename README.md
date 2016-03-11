
###composer

```json
{
    "require": {
        "lukesnowden/google-shopping-feed": "dev-master"
    }
}
```

###Usage

```php
use LukeSnowden\GoogleShoppingFeed\Containers\GoogleShopping;

GoogleShopping::title('Test Feed');
GoogleShopping::link('http://example.com/');
GoogleShopping::description('Our Google Shopping Feed');

foreach( $products as $product ) {

	$item = GoogleShopping::createItem();
	$item->id($id);
	$item->title($title);
	$item->price($price);
	$item->mpn($SKU);
	$item->sale_price($salePrice);
	$item->link($link);
	$item->image_link($imageLink);
	...
	...

	/** create a variant */
	$variant = $item->variant();
	$variant->size($variant::LARGE);
	$variant->color('Red');

	/**
	 * One thing to note, if creating variants, delete the initial object after you've done,
	 * Google no longer needs it!
	 *
	 * $item->delete();
	 *
	 */

}

// boolean value indicates output to browser
GoogleShopping::asRss(true);

```

###Category Taxonomies

returns a list of the categories. The list is updated daily from Googles Documentation

```php
$googleCategories = GoogleShopping::categories();
```