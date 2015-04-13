<?php namespace LukeSnowden\GoogleShoppingFeed;

class Node {

	/**
	 * [$name description]
	 * @var null
	 */
	protected $name = null;

	/**
	 * [$namespace description]
	 * @var null
	 */
	protected $_namespace = null;

	/**
	 * [$value description]
	 * @var string
	 */
	protected $value = '';

	/**
	 * [$cdata description]
	 * @var boolean
	 */
	protected $cdata = false;

	/**
	 * [__construct description]
	 * @param [type] $name [description]
	 */
	public function __construct( $name ) {
		$this->name = $name;
		return $this;
	}

	/**
	 * [namespace description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function _namespace( $value ) {
		$this->_namespace = $value;
		return $this;
	}

	/**
	 * [addCdata description]
	 */
	public function addCdata() {
		$this->cdata = true;
		return $this;
	}

	/**
	 * [value description]
	 * @param  [type] $value [description]
	 * @return [type]        [description]
	 */
	public function value( $value ) {
		$this->value = $value;
		return $this;
	}

	/**
	 * [get description]
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public function get( $key ) {
		return $this->{$key};
	}

	/**
	 * [toXmlNode description]
	 * @param  [type] $parent [description]
	 * @return [type]         [description]
	 */
	public function attachNodeTo( $parent ) {

		if( $this->cdata && ! preg_match( "#^<!\[CDATA#is", $this->value ) ) {
			$this->value = "<![CDATA[{$this->value}]]>";
		}
		$parent->addChild( $this->name, '', $this->_namespace );
		$parent->{$this->name} = $this->value;
	}

}