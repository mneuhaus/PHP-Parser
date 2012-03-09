<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

$party = PHPParser_Builder::createClass("Party");

/**
 * Dir Templates:
 * Templates
 *  - Property
 *     - DateTime.php
 *     - Relation.php
 *     - String.php  
 */
$builder = new PHPParser_Builder(dirname(__FILE__) . "/Templates/");

$party->add(
	$builder->from("Property_String")
			->with(array("property" => "title"))
			->getStatements());

// doesn't get added because the statements already exist
$party->add(
	$builder->from("Property_DateTime")
			->with(array("property" => "title"))
			->getStatements());

$party->add(
	$builder->from("Property_DateTime")
			->with(array("property" => "date"))
			->getStatements());

$party->add(
	$builder->from("Property_Relation")
			->with(array(
				"property" => "guest",
				"properties" => "guests", 
				"class" => "Guest"))
			->getStatements());

// This call adds all statements problem is, that the __construct already exists
// By Default CONFLICT_IGNORE will ignore this new _construct in favor of the existing
// since we now this construct only has a new initiation in it we specify 
// CONFLICT_APPEND as second argument, to append the Methods logic to 
// methods that already exist
$party->add($builder->from("Property_Relation")
			->with(array(
				"property" => "beer",
				"properties" => "beers",
				"class" => "Beer"))
			->getStatements(), PHPParser_Builder::CONFLICT_APPEND);


echo "<pre>";
echo htmlspecialchars(PHPParser_Builder::render($party));
echo "</pre>";

?>