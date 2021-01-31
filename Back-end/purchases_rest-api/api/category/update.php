<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../../repository/CategoryRepository.php";
require_once "../../models/Category.php";

$categoryRepo = new CategoryRepository();

$data = json_decode(file_get_contents("php://input"));

$category = new Category();
$category->setId($data->ID);
$category->setName($data->name);
$category->setDescription($data->description);

echo $categoryRepo->update($category);
