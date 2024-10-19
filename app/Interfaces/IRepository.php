<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface IRepository {
    public function findAll(): Collection;
    public function findById(int $id): ?IEntity;

    public function create(array $data): IEntity;
    public function update(int $id, array $data): ?IEntity;

    public function delete(int $id): bool;
}
