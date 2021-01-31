<?php

include_once "ICategoryRepository.php";
require_once 'C:\xampp\htdocs\purchases_rest-api\models\Category.php';
require_once 'C:\xampp\htdocs\purchases_rest-api\config\Database.php';

class CategoryRepository implements ICategoryRepository {
  private $conn;

  public function __construct() {
    $database = new Database();
    $this->conn = $database->connect();
  }

  public function getAll(): array {
    $stmt = $this->conn->prepare("SELECT * FROM purchases_categories");
    $stmt->execute();
    $categoriesArr = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $category = new Category();

      $category->setId((int)$row["ID"]);
      $category->setName($row["Name"]);
      $category->setDescription($row["Description"]);

      array_push($categoriesArr, $category);
    }

    return $categoriesArr;
  }

  public function getById(int $id): Category {
    $stmt = $this->conn->prepare("SELECT * FROM purchases_categories WHERE ID = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $category = new Category();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $category->setId((int)$row["ID"]);
      $category->setName($row["Name"]);
      $category->setDescription($row["Description"]);
    }

    return $category;
  }

  public function insert(Category $category): string {
    $stmt = $this->conn->prepare("INSERT INTO purchases_categories
                                  (Name, Description)
                                  VALUES (:name, :description)");
    $name = $category->getName();
    $description = $category->getDescription();

    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);

    if ($stmt->execute()) {
      return "Successfully added!";
    } else {
      return "Something went wrong!";
    }
  }

  public function update(Category $category): string {
    $stmt = $this->conn->prepare("UPDATE purchases_categories
                                  SET Name = :name,
                                      Description = :description
                                  WHERE ID = :id");
    $id = $category->getId();
    $name = $category->getName();
    $description = $category->getDescription();

    $stmt->bindParam(":id", $id);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":description", $description);

    if ($stmt->execute()) {
      return "Successfully updated!";
    } else {
      return "Something went wrong!";
    }
  }

  public function delete(int $id): string {
    $stmt = $this->conn->prepare("DELETE FROM purchases_categories WHERE ID = :id");
    $stmt->bindParam(":id", $id);
    if ($stmt->execute()) {
      return "Successfully deleted!";
    } else {
      return "Something went wrong!";
    }
  }
}
