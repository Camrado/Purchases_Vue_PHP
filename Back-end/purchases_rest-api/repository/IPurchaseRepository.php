<?php

interface IPurchaseRepository {
  public function getAll(): array;
  public function getById(int $id): Purchase;
  public function insert(Purchase $purchase): string;
  public function update(Purchase $purchase): string;
  public function delete(int $id): string;
}
