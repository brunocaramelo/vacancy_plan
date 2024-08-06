<?php

namespace Tests\Feature\Http\Controllers;

use App\Exceptions\HolidayNotFoundException;
use App\Services\HolidayPlanService;
use App\Repositories\HolidayPlanRepository;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class HolidayUnitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        \Artisan::call('migrate');
        \Artisan::call('db:seed');
    }

    public function test_create_holiday_success()
    {
        $response = (new HolidayPlanService(new HolidayPlanRepository()))->store([
            "title" => "Any holiday",
            "date"=> "2024-02-14",
            "description"=> "description description description",
            "location"=> "testing location",
            "participants" => [
                [
                    "name" => "Hey",
                    "last_name" => "You",
                    "email" => "heyou@emai.com"
                ],
            ]
        ]);

        $this->assertArrayHasKey('status', $response);
        $this->assertArrayHasKey('message', $response);

        $this->assertEquals('success', $response['status']);
    }

    public function test_find_fail_priority()
    {
        $this->expectException(HolidayNotFoundException::class);

        $id = "as4s36aw-dwe0-4383-a67e-4q5ada7scv7b";

        (new HolidayPlanService(new HolidayPlanRepository()))->getVerboseById($id);

    }

    public function test_find_success_holiday()
    {
        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $response = (new HolidayPlanService(new HolidayPlanRepository()))->getVerboseById($id)->toArray();

        $this->assertArrayHasKey('title', $response);
        $this->assertEquals("Valentines Day", $response['title']);
    }

    public function test_delete_success_holiday()
    {
        $id = "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b";

        $response = (new HolidayPlanService(new HolidayPlanRepository()))->delete($id);

        $this->assertEquals(true, $response);
    }

    public function test_delete_fail_holiday()
    {
        $id = "as4s36aw-dwe0-4383-a67e-4q5ada7scv7b";

        $this->expectException(HolidayNotFoundException::class);

        $response = (new HolidayPlanService(new HolidayPlanRepository()))->delete($id);

        $this->assertArrayHasKey('message', $response);
        $this->assertEquals(10, $response['message']);
    }

    public function test_search_success_with_results()
    {
        $response = (new HolidayPlanService(new HolidayPlanRepository()))->searchAndPaginate([])->toArray();

        $this->assertArrayHasKey('current_page', $response);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('per_page', $response);
        $this->assertArrayHasKey('to', $response);
        $this->assertArrayHasKey('total', $response);

        $this->assertEquals(1, $response['total']);
        $this->assertEquals("Valentines Day", $response['data'][0]['title']);
        $this->assertEquals('2024-02-14', $response['data'][0]['date']);
    }

}
