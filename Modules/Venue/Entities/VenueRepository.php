<?php

namespace Modules\Venue\Entities;

use App\Interfaces\Entities\IVenueEntity;
use App\Interfaces\Repositories\IVenueRepository;
use Illuminate\Support\Collection;

class VenueRepository implements IVenueRepository {
    public function findAll(): Collection
    {
        return Venue::all();
    }

    public function findById(int $id): ?Venue
    {
        return Venue::find($id);
    }

    public function create(array $data): Venue
    {
        return Venue::create($data);
    }

    public function update(int $id, array $data): ?Venue
    {
        $venue = Venue::find($id);

        if(!$venue) {
            return null;
        }

        $venue->update($data);

        return $venue;
    }

    public function delete(int $id): bool
    {
        return Venue::destroy($id);
    }
}
