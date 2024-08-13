<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
    }



    public function test_login_success()
    {
        $this->postJson('/api/login',[
            "email" => "admin@test.com",
            "password"=> "password",
        ])->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'message',
            'access_token',
        ]);

    }
    public function test_login_fail()
    {
        $this->postJson('/api/login',[
            "email" => "admin@test.com",
            "password"=> "password-error",
        ])->assertStatus(400)
        ->assertJsonStructure([
            'status',
            'message',
        ]);

    }


}
