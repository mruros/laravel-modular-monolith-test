<?php

namespace Modules\Event\Entities;

use App\Interfaces\Entities\IEventEntity;
use App\Interfaces\Entities\ITicketPurchaseEntity;
use App\Interfaces\Entities\IVenueEntity;
use App\Interfaces\Repositories\ITicketPurchaseRepository;
use App\Interfaces\Repositories\IVenueRepository;
use App\Models\BaseModel;
use DateTimeImmutable;
use Illuminate\Support\Collection;

class Event extends BaseModel implements IEventEntity
{
    protected $fillable = ['name', 'date', 'venue_id'];

    protected IVenueRepository $venueRepo;
    protected ITicketPurchaseRepository $ticketPurchaseRepo;

    protected function __construct(IVenueRepository $venueRepo, ITicketPurchaseRepository $ticketPurchaseRepo)
    {
        $this->venueRepo = $venueRepo;
        $this->ticketPurchaseRepo = $ticketPurchaseRepo;
    }

    public function getVenue(): IVenueEntity
    {
        return $this->venueRepo->findById($this->venue_id);
    }

    public function setVenue(IVenueEntity $venue): void
    {
        $this->venue_id = $venue->getId();
        $this->save();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
        $this->save();
    }

    public function getAvailableTickets(): int
    {
        return $this->available_tickets;
    }

    public function setAvailableTickets(int $availableTickets): void
    {
        $this->available_tickets = $availableTickets;
        $this->save();
    }

    public function getTicketSalesEndDate(): DateTimeImmutable
    {
        return $this->ticket_sales_end_date;
    }

    public function setTicketSalesEndDate(DateTimeImmutable $ticketSalesEndDate): void
    {
        $this->ticket_sales_end_date = $ticketSalesEndDate;
        $this->save();
    }

    public function getTicketPurchases(): Collection
    {
        $ticketPurchases = $this->ticketPurchaseRepo->findAll()->filter(function(ITicketPurchaseEntity $ticketPurchase) {
            return $ticketPurchase->getEvent()->getId() === $this->getId();
        });

        return $ticketPurchases;
    }

    public function areSeatsAvailable(): bool
    {
        return $this->getAvailableTickets() - $this->getTicketPurchases()->count() > 0;
    }
}
