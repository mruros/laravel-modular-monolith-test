<?php

namespace Modules\Payment\Entities;

use App\Interfaces\Repositories\ITicketPurchaseRepository;
use Illuminate\Support\Collection;

class TicketPurchaseRepository implements ITicketPurchaseRepository {
    public function findAll(): Collection
    {
        return TicketPurchase::all();
    }

    public function findById(int $id): ?TicketPurchase
    {
        return TicketPurchase::find($id);
    }

    public function findByEmail(string $email): Collection
    {
        return TicketPurchase::where('email', $email)->get();
    }

    public function create(array $data): TicketPurchase
    {
        return TicketPurchase::create($data);
    }

    public function update(int $id, array $data): ?TicketPurchase
    {
        $ticketPurchase = TicketPurchase::find($id);

        if(!$ticketPurchase) {
            return null;
        }

        $ticketPurchase->update($data);

        return $ticketPurchase;
    }

    public function delete(int $id): bool
    {
        return TicketPurchase::destroy($id);
    }
}
