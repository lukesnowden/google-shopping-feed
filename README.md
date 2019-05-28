
# Google Shopping Feed

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/lukesnowden/google-shopping-feed.svg?style=flat-square)](https://packagist.org/packages/lukesnowden/google-shopping-feed)

### Composer
How to install with composer.

```json
{
    "require": {
        "lukesnowden/google-shopping-feed": "^2.2"
    }
}
```

### Usage
Example of Google feed generation.

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

### Category Taxonomies

Returns a list of the categories. The list is updated daily from Googles Documentation

```php
$lang = 'gb';
$googleCategories = GoogleShopping::categories($lang);
```

`$lang` can be one of these Google supported languages: au, br, cn, cz, de, dk, es, fr, gb, it, jp, nl, no, pl, ru, sw, tr, us.
