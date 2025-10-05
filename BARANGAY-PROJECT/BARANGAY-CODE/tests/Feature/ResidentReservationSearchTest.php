<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResidentReservationSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_resident_search_is_scoped_to_authenticated_user()
    {
        $servicePc = Service::factory()->create(['name' => 'PC Rental']);
        $serviceOther = Service::factory()->create(['name' => 'Other']);

        $alice = User::factory()->create();
        $bob = User::factory()->create();

        // Bob has a reservation that matches the search term
        Reservation::factory()->create([
            'user_id' => $bob->id,
            'service_id' => $servicePc->id,
            'reservation_date' => now()->toDateString(),
        ]);

        // Alice has a different reservation that should be returned for Alice
        $aliceRes = Reservation::factory()->create([
            'user_id' => $alice->id,
            'service_id' => $serviceOther->id,
            'reservation_date' => now()->toDateString(),
        ]);

        $response = $this->actingAs($alice)
            ->get(route('resident.reservation', ['q' => 'PC']));

        $response->assertOk();
        // Ensure Bob's reservation is not visible
        $response->assertDontSee('PC Rental');
        // Alice should still see her own reservation list page; not strictly asserting service name
        $response->assertSee('My Reservations');
    }
}


