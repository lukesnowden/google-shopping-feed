<?php

namespace LukeSnowden\GoogleShoppingFeed\Exceptions;

use Exception;

class MissingIdentifierException extends Exception {

	/**
	 * [__construct description]
	 * @param [type]  $message [description]
	 * @param integer $code    [description]
	 */
	public function __construct( $message = null, $code = 0 )
	{
		if ( ! $message ) {
			throw new $this( 'Unknown ' . get_class( $this ) );
		}
		parent::__construct( $message, $code );
	}

}