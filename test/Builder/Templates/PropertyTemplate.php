<?php

class PropertyTemplate {
	/**
	 * My awesome __Property
	 *
	 * @var string
	 **/
	protected $__property;

	/**
	 * Getter for __property
	 *
	 * @return string
	 **/
	public function get__Property() {
		return $this->__property;
	}

	/**
	 * Setter for __property
	 *
	 * @param string $__property
	 **/
	public function set__Property($__property) {
		// some nonsense logic
		if($__property == "foo"){
			throw new Exception("Foo isn't allowed here...", 1);
		}
		$this->__property = $__property;
	}
}

?>