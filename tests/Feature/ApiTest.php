<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['access_token']);
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logged out']);
    }

    public function test_user_can_get_account_info()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/account');

        $response->assertStatus(200)
            ->assertJsonStructure(['balance', 'transactions']);
    }

    public function test_user_can_deposit_money()
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id, 'balance' => 0]);
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/deposit', [
            'amount' => 100
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Deposit successful',
                'balance' => 100
            ]);
    }

    public function test_user_can_transfer_money()
    {
        $user1 = User::factory()->create();
        $account1 = Account::factory()->create(['user_id' => $user1->id, 'balance' => 100]);

        $user2 = User::factory()->create();
        $account2 = Account::factory()->create(['user_id' => $user2->id, 'balance' => 0]);

        $token = $user1->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/transfer', [
            'amount' => 50,
            'to_account_id' => $account2->id
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Transfer successful',
                'balance' => 50
            ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account1->id,
            'balance' => 50
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $account2->id,
            'balance' => 50
        ]);
    }
}
