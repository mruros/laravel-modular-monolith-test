<?php

namespace Modules\Venue\Entities;

use App\Interfaces\Entities\IVenueEntity;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;

class Venue extends BaseModel implements IVenueEntity
{
    protected $fillable = ['name', 'capacity'];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): void
    {
        if($capacity < 0) {
            $capacity = 0;
        }

        $this->capacity = $capacity;
    }
}
