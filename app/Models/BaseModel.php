<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\IEntity;
use DateTimeImmutable;

class BaseModel extends Model implements IEntity
{
    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
