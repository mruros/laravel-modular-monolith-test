<?php

namespace App\Interfaces\Repositories;

use App\Interfaces\Entities\IEventEntity;
use App\Interfaces\IRepository;

interface IEventRepository extends IRepository {
    public function findById(int $id): ?IEventEntity;

    public function create(array $data): IEventEntity;
    public function update(int $id, array $data): ?IEventEntity;

    public function delete(int $id): bool;
}
