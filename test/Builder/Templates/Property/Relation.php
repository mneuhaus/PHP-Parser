<?php

class Property_Relation {
	/**
	 * My awesome __Property
	 *
	 * @var SplObjectStorage<__class>
	 **/
	protected $__properties;

	/**
	 * Getter for __property
	 *
	 * @return SplObjectStorage
	 **/
	public function get__Property() {
		return $this->__properties;
	}

	/**
	 * Setter for __property
	 *
	 * @param SplObjectStorage $__property
	 **/
	public function set__Property($__property) {
		$this->__properties = $__property;
	}

	/**
	 * Adds a __Property
	 *
	 * @param __class $__property
	 * @return void
	 */
	public function add__Property(__class $__property) {
		if(!$__property instanceof __class){
			throw new Exception("Only __class allowed", 1);
		}
		$this->__properties->attach($__property);
	}

	/**
	 * Removes a __Property
	 *
	 * @param __class $__property The __Property to be removed
	 * @return void
	 */
	public function remove__Property(__class $__property) {
		$this->__properties->detach($__property);
	}
}

?>