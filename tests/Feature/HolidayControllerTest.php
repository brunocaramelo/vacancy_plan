<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

class HolidayControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
    }

    private function sanctumTokenGenerate()
    {
        return Sanctum::actingAs(
            \App\Models\User::factory()->create(),
            ['view-tasks']
        );
    }

    public function test_create_holiday_success()
    {
        $this->sanctumTokenGenerate();

        $this->postJson('/api/holidays',[
            "title" => "Holiday any",
            "date"=> "2025-10-10",
            "description"=> "description description description",
            "location"=> "location location",
            "participants" => [
                [
                    "name" => "Hey",
                    "last_name" => "You",
                    "email" => "heyou@email.com"
                ],
          ]
        ])->assertStatus(201)
        ->assertJsonStructure([
            'status',
            'message',
            'data',
        ]);

    }
    public function test_create_holiday_fail()
    {
        $this->sanctumTokenGenerate();

        $this->postJson('/api/holidays',[
            "title" => 1,
            "date"=> "2024-02-14",
            "description"=> "description description description",
            "location"=> null,
            "participants" => [
                [
                    "name" => "Hey",
                    "last_name" => "You",
                    "email" => "heyou0emaicom"
                ],
          ]
        ])->assertStatus(422)
        ->assertJsonPath('errors.title.0', 'Title must be string')
        ->assertJsonPath('errors.date.0', 'Date must be Unique')
        ;

    }
    public function test_update_holiday_success()
    {
        $this->sanctumTokenGenerate();

        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $this->putJson('/api/holidays/'.$id,[
            "title" => "changed",
            "date"=> "2024-02-14",
            "description"=> "description description description",
            "location"=> "location",
            "participants" => [
                [
                    "name" => "Hey",
                    "last_name" => "You",
                    "email" => "heyou@emai.com"
                ],
          ]
        ])->assertStatus(200);

    }
    public function test_update_holiday_fail()
    {
        $this->sanctumTokenGenerate();

        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $this->putJson('/api/holidays/'.$id,[
            "title" => 1,
            "date"=> "2024-02-14",
            "description"=> "description description description",
            "location"=> null,
            "participants" => [
                [
                    "name" => "Hey",
                    "last_name" => "You",
                    "email" => "heyou0emaicom"
                ],
          ]
        ])->assertStatus(422)
        ->assertJsonPath('errors.title.0', 'Title must be string');

    }

    public function test_search_list_with_result_success()
    {
        $this->sanctumTokenGenerate();

        $this->getJson('/api/holidays')
        ->assertStatus(200)
        ->assertJsonStructure([
            'current_page',
            'data',
            'per_page',
            'to',
            'total',
        ])->assertJsonPath('total', 1)
        ->assertJsonPath('data.0.title', 'Valentines Day')
        ->assertJsonPath('data.0.date', '2024-02-14')
        ->assertJsonPath('data.0.description', 'Valentines Day Description')
        ->assertJsonPath('data.0.participants.0.name', "First")
        ->assertJsonPath('data.0.participants.0.last_name', "User 1")
        ->assertJsonPath('data.0.participants.0.email', "first@email.com")
        ->assertJsonPath('data.0.participants.1.name', "Second")
        ->assertJsonPath('data.0.participants.1.last_name', "User 2")
        ->assertJsonPath('data.0.participants.1.email', "second@email.com");

    }

    public function test_search_list_without_results_success()
    {
        $this->sanctumTokenGenerate();

        $this->get('/api/holidays?'. \Arr::query([
            'date' => "2040-11-11",
            'title' => "Not Found",
            'participant_email' => "NotFound",
            'participant_name' => "NotFound",
        ]))->assertStatus(200)
        ->assertJsonStructure([
            'current_page',
            'data',
            'per_page',
            'to',
            'total',
        ])->assertJsonPath('total', 0);

    }

    public function test_get_holiday_with_default_success()
    {
        $this->sanctumTokenGenerate();

        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $this->getJson("/api/holidays/$id")
        ->assertStatus(200)
        ->assertJsonPath('title', 'Valentines Day')
        ->assertJsonPath('date', '2024-02-14')
        ->assertJsonPath('description', 'Valentines Day Description')
        ->assertJsonPath('participants.0.name', "First")
        ->assertJsonPath('participants.0.last_name', "User 1")
        ->assertJsonPath('participants.0.email', "first@email.com")
        ->assertJsonPath('participants.1.name', "Second")
        ->assertJsonPath('participants.1.last_name', "User 2")
        ->assertJsonPath('participants.1.email', "second@email.com");
    }

    public function test_get_priority_with_fail()
    {
        $this->sanctumTokenGenerate();

        $id = "as4s36aw-dwe0-4383-a67e-4q5ada7scv7b";

        $this->getJson("/api/holidays/$id")
        ->assertStatus(404)
        ->assertJsonStructure([
            'message',
            'status',
        ])->assertJsonPath('message', 'Holiday not found')
        ->assertJsonPath('status', 'fail');
    }

    public function test_delete_priority_with_fail()
    {
        $this->sanctumTokenGenerate();

        $id = "as4s36aw-dwe0-4383-a67e-4q5ada7scv7b";

        $this->deleteJson("/api/holidays/$id")
        ->assertStatus(404)
        ->assertJsonStructure([
            'message',
        ])->assertJsonPath('message', 'Holiday not found');
    }

    public function test_delete_priority_with_success()
    {
        $this->sanctumTokenGenerate();

        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $this->deleteJson("/api/holidays/$id")
        ->assertStatus(200)
        ->assertJsonStructure([
            'message',
        ])->assertJsonPath('message', 'Holiday Removed');
    }

}
