<?php

namespace Modules\Event\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Event\Entities\Event;
use Modules\Venue\Entities\Venue;
use Modules\Payment\Entities\TicketPurchase;
use Tests\TestCase;
use DateTimeImmutable;

class EventFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_events_with_available_tickets()
    {
        $venue1 = Venue::factory()->create([
            'name' => 'Venue 1',
            'capacity' => 100,
        ]);

        $venue2 = Venue::factory()->create([
            'name' => 'Venue 2',
            'capacity' => 50,
        ]);

        $event1 = Event::factory()->create([
            'name' => 'Event 1',
            'venue_id' => $venue1->id,
            'available_tickets' => 100,
            'ticket_sales_end_date' => now()->addDays(5),
        ]);

        $event2 = Event::factory()->create([
            'name' => 'Event 2',
            'venue_id' => $venue2->id,
            'available_tickets' => 50,
            'ticket_sales_end_date' => now()->addDays(5),
        ]);

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJson([
                [
                    'event_name' => 'Event 1',
                    'available_tickets' => 100,
                    'venue_name' => 'Venue 1',
                    'ticket_sales_end_date' => $event1->ticket_sales_end_date->format('Y-m-d H:i:s'),
                ],
                [
                    'event_name' => 'Event 2',
                    'available_tickets' => 50,
                    'venue_name' => 'Venue 2',
                    'ticket_sales_end_date' => $event2->ticket_sales_end_date->format('Y-m-d H:i:s'),
                ]
            ]);
    }

    /** @test */
    public function it_allows_purchasing_tickets_when_available()
    {
        $venue = Venue::factory()->create([
            'name' => 'Main Hall',
            'capacity' => 100,
        ]);

        $event = Event::factory()->create([
            'name' => 'Music Concert',
            'venue_id' => $venue->id,
            'available_tickets' => 10,
            'ticket_sales_end_date' => now()->addDays(5),
        ]);

        $response = $this->postJson("/api/events/{$event->id}/purchase", [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'transaction_id',
            ]);
    }

    /** @test */
    public function it_prevents_purchasing_tickets_with_duplicate_email()
    {
        $venue = Venue::factory()->create([
            'name' => 'Main Hall',
            'capacity' => 100,
        ]);

        $event = Event::factory()->create([
            'name' => 'Music Concert',
            'venue_id' => $venue->id,
            'available_tickets' => 10,
            'ticket_sales_end_date' => now()->addDays(5),
        ]);

        // First purchase
        TicketPurchase::factory()->create([
            'event_id' => $event->id,
            'email' => 'duplicate@example.com',
        ]);

        // Attempt to purchase again with the same email
        $response = $this->postJson("/api/events/{$event->id}/purchase", [
            'email' => 'duplicate@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'Email already used for this event.',
            ]);
    }

    /** @test */
    public function it_prevents_purchasing_tickets_for_sold_out_events()
    {
        $venue = Venue::factory()->create([
            'name' => 'Main Hall',
            'capacity' => 1,
        ]);

        // This could also be tested by checking all ticket purchases (which'd necessiate seeding some)
        $event = Event::factory()->create([
            'name' => 'Sold Out Event',
            'venue_id' => $venue->id,
            'available_tickets' => 0, // No tickets available
            'ticket_sales_end_date' => now()->addDays(5),
        ]);

        $response = $this->postJson("/api/events/{$event->id}/purchase", [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'No available seats for this event.',
            ]);
    }

    /** @test */
    public function it_prevents_purchasing_tickets_for_closed_events()
    {
        $venue = Venue::factory()->create([
            'name' => 'Main Hall',
            'capacity' => 100,
        ]);

        $event = Event::factory()->create([
            'name' => 'Past Event',
            'venue_id' => $venue->id,
            'available_tickets' => 10,
            'ticket_sales_end_date' => now()->subDay(), // Sales ended
        ]);

        $response = $this->postJson("/api/events/{$event->id}/purchase", [
            'email' => 'user@example.com',
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'error' => 'The event is closed.',
            ]);
    }
}
