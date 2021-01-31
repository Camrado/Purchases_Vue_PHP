<?php

class Category {
  // Properties
  public $ID;
  public $name;
  public $description;

  public function __construct($category) {
    $this->ID = (int)$category["ID"];
    $this->name = $category["Name"];
    $this->description = $category["Description"];
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
