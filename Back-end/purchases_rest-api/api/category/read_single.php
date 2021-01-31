<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../../repository/CategoryRepository.php";

$categoryRepo = new CategoryRepository();

echo json_encode($categoryRepo->getById($_GET["id"]));
