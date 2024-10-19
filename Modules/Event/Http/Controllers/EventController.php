<?php

namespace Modules\Event\Http\Controllers;

use App\Interfaces\Repositories\IEventRepository;
use App\Interfaces\Repositories\ITicketPurchaseRepository;
use App\Interfaces\Repositories\IVenueRepository;
use DateTimeImmutable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Event\Entities\Event;

class EventController extends Controller
{
    protected $eventRepo;
    protected $venueRepo;
    protected $ticketPurchaseRepo;

    public function __construct(IEventRepository $eventRepo, IVenueRepository $venueRepo, ITicketPurchaseRepository $ticketPurchaseRepo)
    {
        $this->eventRepo = $eventRepo;
        $this->venueRepo = $venueRepo;
        $this->ticketPurchaseRepo = $ticketPurchaseRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $events = Event::all();

        $formattedEvents = $events->map(function($event) {
            return [
                'event_name' => $event->getName(),
                'available_tickets' => $event->getAvailableTickets() - $event->getTicketPurchases(),
                'venue_name' => $event->getVenue()->getName(),
                'ticket_sales_end_date' => $event->getTicketSalesEndDate()->format('Y-m-d H:i:s')
            ];
        });

        return response()->json($formattedEvents);
    }

   public function purchaseTicket(Request $request, int $eventId)
    {
        $email = $request->email;

        $event = $this->eventRepo->findById($eventId);
        $remainingSeats = $event->getAvailableTickets() - $event->getTicketPurchases();

        if ($remainingSeats < $request->quantity) {
            return response()->json(['error' => 'No available seats for this event.'], 400);
        }

        if($event->getTicketSalesEndDate() < new DateTimeImmutable()) {
            return response()->json(['error' => 'The event is closed.'], 400);
        }

        if(!$email) {
            return response()->json(['error' => 'Email is required.'], 400);
        }

        $ticketPurchasesWithSameEmail = $this->ticketPurchaseRepo->findByEmail($email);
        $relevantPurchases = $ticketPurchasesWithSameEmail->filter(function($purchase) use ($event) {
            return $purchase->getEvent()->getId() === $event->getId();
        });

        if($relevantPurchases->count() > 0) {
            return response()->json(['error' => 'Email already used for this event.'], 400);
        }

        // do some processing here to generate a transaction id
        // in the meantime, just generating a random number here
        $transactionId = rand(100000, 999999);

        $this->ticketPurchaseRepo->create([
            'event_id' => $event->getId(),
            'quantity' => $request->quantity,
            'transaction_id' => $transactionId
        ]);

        return response()->json(['transaction_id' => $transactionId]);
    }
}
