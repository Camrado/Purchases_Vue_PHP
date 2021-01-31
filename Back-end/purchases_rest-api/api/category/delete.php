<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../../repository/CategoryRepository.php";

$categoryRepo = new CategoryRepository();

echo $categoryRepo->delete($_GET["id"]);
