<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once "../../repository/PurchaseRepository.php";

$purchaseRepo = new PurchaseRepository();

echo json_encode($purchaseRepo->getById($_GET["id"]));
