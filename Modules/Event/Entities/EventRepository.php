<?php

namespace Modules\Event\Entities;

use App\Interfaces\Entities\IVenueEntity;
use App\Interfaces\Repositories\IEventRepository;
use App\Interfaces\Repositories\IVenueRepository;
use Illuminate\Support\Collection;

class EventRepository implements IEventRepository {
    public function findAll(): Collection
    {
        return Event::all();
    }

    public function findById(int $id): ?Event
    {
        return Event::find($id);
    }

    public function create(array $data): Event
    {
        return Event::create($data);
    }

    public function update(int $id, array $data): ?Event
    {
        $event = Event::find($id);

        if(!$event) {
            return null;
        }

        $event->update($data);

        return $event;
    }

    public function delete(int $id): bool
    {
        return Event::destroy($id);
    }
}
