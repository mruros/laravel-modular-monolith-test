<?php

namespace App\Interfaces;

use DateTimeImmutable;

interface IEntity
{
    public function getId(): int;
    public function setId(int $id): void;
}
