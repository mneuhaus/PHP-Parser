<?php

require_once dirname(__FILE__) . '/../bootstrap.php';

$newClass = PHPParser_Builder::createClass("NewClass");


// Get all Statements from the Template and add them to the new Class
// explicit context provided to override the context set by setContext
$propertyTemplate = new PHPParser_Template(dirname(__FILE__) . "/Templates/PropertyTemplate.php");
$newClass->add($propertyTemplate->getStatements(array("property" => "title")));
$newClass->add($propertyTemplate->getStatements(array("property" => "description")));
$newClass->add($propertyTemplate->getStatements(array("property" => "created")));

// Set the context to be used for now on
$propertyTemplate = new PHPParser_Template(dirname(__FILE__) . "/Templates/MethodsTemplate.php");
$newClass->add($propertyTemplate->getMethod("someNonesense"));

echo "<pre>";
echo htmlspecialchars(PHPParser_Builder::render($newClass));
echo "</pre>";

?>