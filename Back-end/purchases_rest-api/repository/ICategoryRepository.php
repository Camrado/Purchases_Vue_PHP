<?php

interface ICategoryRepository {
  public function getAll(): array;
  public function getById(int $id): Category;
  public function insert(Category $category): string;
  public function update(Category $category): string;
  public function delete(int $id): string;
}
