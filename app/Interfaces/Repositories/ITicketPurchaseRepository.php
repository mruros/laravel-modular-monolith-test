<?php

namespace App\Interfaces\Repositories;

use App\Interfaces\Entities\IPaymentEntity;
use App\Interfaces\Entities\ITicketPurchaseEntity;
use App\Interfaces\IRepository;
use Illuminate\Support\Collection;

interface ITicketPurchaseRepository extends IRepository {
    public function findById(int $id): ?ITicketPurchaseEntity;
    public function findByEmail(string $email): Collection;

    public function create(array $data): ITicketPurchaseEntity;
    public function update(int $id, array $data): ?ITicketPurchaseEntity;

    public function delete(int $id): bool;
}
