<?php

class Property_DateTime {
	/**
	 * My awesome __Property
	 *
	 * @var DateTime
	 **/
	protected $__property;

	/**
	 * Getter for __property
	 *
	 * @return DateTime
	 **/
	public function get__Property() {
		return $this->__property;
	}

	/**
	 * Setter for __property
	 *
	 * @param DateTime $__property
	 **/
	public function set__Property($__property) {
		if(!$__property instanceof DateTime){
			throw new Exception("Only DateTime allowed", 1);
		}
		$this->__property = $__property;
	}
}

?>