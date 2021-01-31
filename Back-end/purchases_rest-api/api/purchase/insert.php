<?php

// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

require_once "../../repository/PurchaseRepository.php";
require_once "../../models/Purchase.php";

$purchaseRepo = new PurchaseRepository();

$data = json_decode(file_get_contents("php://input"));

$purchase = new Purchase();
$purchase->setDate($data->date);
$purchase->setCategory($data->category);
$purchase->setItem($data->item);
$purchase->setPrice($data->price);
$purchase->setQuantity($data->quantity);

echo $purchaseRepo->insert($purchase);
