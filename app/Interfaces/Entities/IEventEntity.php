<?php

namespace App\Interfaces\Entities;

use App\Interfaces\IEntity;
use DateTimeImmutable;
use Illuminate\Support\Collection;

interface IEventEntity extends IEntity {
    public function getVenue(): IVenueEntity;
    public function setVenue(IVenueEntity $venue): void;

    public function getName(): string;
    public function setName(string $name): void;

    public function getAvailableTickets(): int;
    public function setAvailableTickets(int $availableTickets): void;

    public function getTicketPurchases(): Collection;
    public function areSeatsAvailable(): bool;

    public function getTicketSalesEndDate(): DateTimeImmutable;
    public function setTicketSalesEndDate(DateTimeImmutable $ticketSalesEndDate): void;
}
