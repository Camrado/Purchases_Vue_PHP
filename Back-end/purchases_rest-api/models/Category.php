<?php

class Category {
  // Properties
  public $ID;
  public $name;
  public $description;

  public function __construct($category) {
    if (is_array($category)) {
      $this->ID = (int)$category["ID"];
      $this->name = $category["Name"];
      $this->description = $category["Description"];
    } else if (is_object($category)) {
      $this->ID = (int)$category->ID;
      $this->name = $category->name;
      $this->description = $category->description;
    }
  }

  // ID
  public function getId() {
    return $this->ID;
  }
  public function setId($ID) {
    $this->ID = $ID;
  }

  // Name
  public function getName() {
    return $this->name;
  }
  public function setName($name) {
    $this->name = $name;
  }

  // Description
  public function getDescription() {
    return $this->description;
  }
  public function setDescription($description) {
    $this->description = $description;
  }
}
