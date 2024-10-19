<?php

namespace App\Interfaces\Entities;

use App\Interfaces\IEntity;

interface IVenueEntity extends IEntity {
    public function getName(): string;
    public function setName(string $name): void;

    public function getCapacity(): int;
    public function setCapacity(int $capacity): void;
}
