<?php

namespace Modules\Payment\Entities;

use App\Interfaces\Entities\IEventEntity;
use App\Interfaces\Entities\ITicketPurchaseEntity;
use App\Interfaces\Repositories\IEventRepository;
use App\Models\BaseModel;

class TicketPurchase extends BaseModel implements ITicketPurchaseEntity
{
    protected $fillable = ['event_id', 'email', 'transaction_id'];

    protected function __construct(IEventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public function getEvent(): IEventEntity
    {
        return $this->eventRepository->findById($this->event_id);
    }

    public function setEvent(IEventEntity $event): void
    {
        $this->event_id = $event->getId();
        $this->save();
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(string $transactionId): void
    {
        $this->transaction_id = $transactionId;
    }
}
