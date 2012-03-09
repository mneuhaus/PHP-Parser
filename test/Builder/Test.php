<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

$newClass = PHPParser_Builder::createClass("Party");


$builder = new PHPParser_Builder(dirname(__FILE__) . "/Templates/");

$newClass->add(
	$builder->from("Property_String")
			->with(array("property" => "title"))
			->getStatements());

$newClass->add(
	$builder->from("Property_Relation")
			->with(array(
				"property" => "guest",
				"properties" => "guests", 
				"class" => "Guest"))
			->getStatements());

$newClass->add(
	$builder->from("Property_DateTime")
			->with(array("property" => "date"))
			->getStatements());

echo "<pre>";
echo htmlspecialchars(PHPParser_Builder::render($newClass));
echo "</pre>";

?>