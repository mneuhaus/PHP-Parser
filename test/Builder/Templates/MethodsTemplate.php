<?php

class MethodsTemplate {

	/**
	 * Some nonesense Method
	 *
	 **/
	public function someNonesense() {
		$nonsense = array("foo", "bar");
		
		foreach ($nonsense as $key => $value) {
			$nonsense[$key] = str_replace("foo", "bar", $value);
		}

		return $nonesense;
	}

}

?>