<?php

namespace App\Interfaces\Repositories;

use App\Interfaces\Entities\IVenueEntity;
use App\Interfaces\IRepository;

interface IVenueRepository extends IRepository {
    public function findById(int $id): ?IVenueEntity;

    public function create(array $data): IVenueEntity;
    public function update(int $id, array $data): ?IVenueEntity;

    public function delete(int $id): bool;
}
