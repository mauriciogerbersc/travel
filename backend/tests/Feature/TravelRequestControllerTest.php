<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelRequestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelRequestControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticateUser($role = 'user')
    {
        $user = User::factory()->create(['role' => $role]);
        $this->actingAs($user);
        return $user;
    }

    public function test_user_can_create_travel_request()
    {
        $user = $this->authenticateUser();

        $data = [
            'requester_name' => 'João',
            'destination' => 'São Paulo',
            'departure_date' => now()->addDays(1)->toDateString(),
            'return_date' => now()->addDays(3)->toDateString(),
        ];

        $response = $this->postJson('/api/travel-requests', $data);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'requester_name' => 'João',
                     'destination' => 'São Paulo',
                     'status' => 'solicitado',
                 ]);
    }

    public function test_user_can_list_own_travel_requests()
    {
        $user = $this->authenticateUser();
        TravelRequestModel::factory()->create(['user_id' => $user->id]);
        TravelRequestModel::factory()->create(); // de outro user

        $response = $this->getJson('/api/travel-requests');

        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
    }

    public function test_admin_can_list_all_travel_requests()
    {
        $admin = $this->authenticateUser('admin');
        TravelRequestModel::factory()->count(3)->create();

        $response = $this->getJson('/api/travel-requests');

        $response->assertStatus(200);
        $this->assertCount(3, $response->json());
    }

    public function test_admin_can_update_status()
    {
        $admin = $this->authenticateUser('admin');
        $request = TravelRequestModel::factory()->create(['status' => 'solicitado']);

        $response = $this->putJson("/api/travel-requests/{$request->id}/status", [
            'status' => 'aprovado',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'aprovado']);
    }

    public function test_user_cannot_update_status()
    {
        $user = $this->authenticateUser();
        $request = TravelRequestModel::factory()->create();

        $response = $this->putJson("/api/travel-requests/{$request->id}/status", [
            'status' => 'aprovado',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_cancel_own_pending_request()
    {
        $user = $this->authenticateUser();
        $request = TravelRequestModel::factory()->create([
            'user_id' => $user->id,
            'status' => 'solicitado',
        ]);

        $response = $this->putJson("/api/travel-requests/{$request->id}/cancel");

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'cancelado']);
    }

    public function test_user_cannot_cancel_approved_request()
    {
        $user = $this->authenticateUser();
        $request = TravelRequestModel::factory()->create([
            'user_id' => $user->id,
            'status' => 'aprovado',
        ]);

        $response = $this->putJson("/api/travel-requests/{$request->id}/cancel");

        $response->assertStatus(405);
    }
}
