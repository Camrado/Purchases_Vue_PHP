<?php

class Purchase {
  // Properties
  public $ID;
  public $date;
  public $category;
  public $item;
  public $price;
  public $quantity;
  public $total_price;

  public function __construct($purchase) {
    $this->ID = (int)$purchase["ID"];
    $this->date = $purchase["Date"];
    $this->category = $purchase["Category"];
    $this->item = $purchase["Item"];
    $this->price = (float)$purchase["Price"];
    $this->quantity = (int)$purchase["Quantity"];
    $this->total_price = (float)$purchase["Total_price"];
  }

  // ID
  public function getId() {
    return $this->ID;
  }
  public function setId($ID) {
    $this->ID = $ID;
  }

  // Date
  public function getDate() {
    return $this->date;
  }
  public function setDate($date) {
    $this->date = $date;
  }

  // Category
  public function getCategory() {
    return $this->category;
  }
  public function setCategory($category) {
    $this->category = $category;
  }

  // Item
  public function getItem() {
    return $this->item;
  }
  public function setItem($item) {
    $this->item = $item;
  }

  // Price
  public function getPrice() {
    return $this->price;
  }
  public function setPrice($price) {
    $this->price = $price;
  }

  // Quantity
  public function getQuantity() {
    return $this->quantity;
  }
  public function setQuantity($quantity) {
    $this->quantity = $quantity;
  }

  // Total price
  public function getTotalPrice() {
    return $this->total_price;
  }
  public function setTotalPrice($total_price) {
    $this->total_price = $total_price;
  }
}
