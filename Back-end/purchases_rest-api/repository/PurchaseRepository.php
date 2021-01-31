<?php

include_once "IPurchaseRepository.php";
require_once 'C:\xampp\htdocs\purchases_rest-api\models\Purchase.php';
require_once 'C:\xampp\htdocs\purchases_rest-api\config\Database.php';

class PurchaseRepository implements IPurchaseRepository {
  private $conn;

  public function __construct() {
    $database = new Database();
    $this->conn = $database->connect();
  }

  public function getAll(): array {
    $stmt = $this->conn->prepare("SELECT * FROM purchases_table");
    $stmt->execute();
    $purchasesArr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $purchase = new Purchase();

      $purchase->setId((int)$row["ID"]);
      $purchase->setDate($row["Date"]);
      $purchase->setItem($row["Item"]);
      $purchase->setCategory($row["Category"]);
      $purchase->setPrice((float)$row["Price"]);
      $purchase->setQuantity((int)$row["Quantity"]);
      $purchase->setTotalPrice((float)$row["Total_price"]);

      array_push($purchasesArr, $purchase);
    }

    return $purchasesArr;
  }

  public function getById(int $id): Purchase {
    $stmt = $this->conn->prepare("SELECT * FROM purchases_table WHERE ID = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $purchase = new Purchase();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $purchase->setId((int)$row["ID"]);
      $purchase->setDate($row["Date"]);
      $purchase->setItem($row["Item"]);
      $purchase->setCategory($row["Category"]);
      $purchase->setPrice((float)$row["Price"]);
      $purchase->setQuantity((int)$row["Quantity"]);
      $purchase->setTotalPrice((float)$row["Total_price"]);
    }

    return $purchase;
  }

  public function insert(Purchase $purchase): string {
    $stmt = $this->conn->prepare("INSERT INTO purchases_table
                                  (Date, Category, Item, Price, Quantity)
                                  VALUES (:date, :category, :item, :price, :quantity)");
    $date = $purchase->getDate();
    $item = $purchase->getItem();
    $price = $purchase->getPrice();
    $quantity = $purchase->getQuantity();
    $category = $purchase->getCategory();

    $stmt->bindParam(":date", $date);
    $stmt->bindParam(":category", $category);
    $stmt->bindParam(":item", $item);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":quantity", $quantity);

    if ($stmt->execute()) {
      return "Successfully added!";
    } else {
      return "Something went wrong!";
    }
  }

  public function update(Purchase $purchase): string {
    $stmt = $this->conn->prepare("UPDATE purchases_table
                                  SET Date = :date,
                                      Category = :category,
                                      Item = :item,
                                      Price = :price,
                                      Quantity = :quantity
                                  WHERE ID = :id");
    $id = $purchase->getId();
    $date = $purchase->getDate();
    $item = $purchase->getItem();
    $price = $purchase->getPrice();
    $quantity = $purchase->getQuantity();
    $category = $purchase->getCategory();

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":date", $date);
    $stmt->bindParam(":category", $category);
    $stmt->bindParam(":item", $item);
    $stmt->bindParam(":price", $price);
    $stmt->bindParam(":quantity", $quantity);

    if ($stmt->execute()) {
      return "Successfully updated!";
    } else {
      return "Something went wrong!";
    }
  }

  public function delete(int $id): string {
    $stmt = $this->conn->prepare("DELETE FROM purchases_table WHERE ID = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
      return "Successfully deleted!";
    } else {
      return "Something went wrong!";
    }
  }
}
