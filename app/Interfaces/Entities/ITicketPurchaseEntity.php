<?php

namespace App\Interfaces\Entities;

use App\Interfaces\IEntity;

interface ITicketPurchaseEntity extends IEntity {
    public function getEvent(): IEventEntity;
    public function setEvent(IEventEntity $event): void;

    public function getEmail(): string;
    public function setEmail(string $email): void;

    public function getTransactionId(): string;
    public function setTransactionId(string $transactionId): void;
}
