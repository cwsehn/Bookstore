<?php 
	
//This is the existing Array for this example...
$existingArray = array("little Blue" => "cat", "twiggy"=>"dog");
var_dump($existingArray);

echo ("<br/>");

//This is how to add a string value to the existing Array at the first available index...
$existingArray[] = "add new string Value at first available indexKey - [0]";
var_dump($existingArray);

echo ("<br/>");

//This is how to add an Assoc. Key with a new string Value to the existing array....
$existingArray["NewKey"] = "add new string Value with Assoc - [NewKey] -key";
var_dump($existingArray);

echo ("<br/>");

//This adds an array value within the existing array at the first available index......
$existingArray[][] = "add new array Value at first available indexKey - [1][0]";
var_dump($existingArray);

echo ("<br/>");


//This adds an inner array value to the existing array with an assocKey......
$existingArray["ArrayKey"][] = "add new array Value with Assoc - [ArrayKey][0] -key";
var_dump($existingArray);

echo ("<br/>");

//This is how to add an inner array to the existing array with an inner assocKey
$existingArray["OuterKey"]["InnerKey"] = "add new array Value with Assoc - [OuterKey][InnerKey] -keys ...";
var_dump($existingArray);

echo ("<br/>");

//This is how to add an inner array to the existing array with an inner assocKey
$existingArray["OuterKey"]["2ndInnerKey"] = "add a second Value to Assoc - [OuterKey] array  - [2ndInnerKey]  ...";
var_dump($existingArray);

echo ("<br/>");

//the rest is gravy if you understand what's above!!!
$existingArray["horsey"]["chesnut"] = "cherokee";
var_dump($existingArray);

echo ("<br/>");

$existingArray[] = "GOPHERS";
var_dump($existingArray);

echo ("<br/>");

$existingArray["WISC"][] = "BADGERS";
var_dump($existingArray);

echo ("<br/>");

