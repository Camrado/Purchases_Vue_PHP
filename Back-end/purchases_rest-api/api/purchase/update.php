<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../../repository/PurchaseRepository.php";
require_once "../../models/Purchase.php";

$purchaseRepo = new PurchaseRepository();

$data = json_decode(file_get_contents("php://input"));

$purchase = new Purchase($data);

echo $purchaseRepo->update($purchase);
