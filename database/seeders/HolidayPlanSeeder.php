<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Holiday;
use App\Models\Participant;
use Illuminate\Support\Str;

class HolidayPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $participantSeedList = [
            [
                "name" => "First",
                "last_name" => "User 1",
                "email" => "first@email.com"
            ],
            [
                "name" => "Second",
                "last_name" => "User 2",
                "email" => "second@email.com"
            ],

        ];

        $holidayInstance = Holiday::create([
            'id' => "df4536bf-d7e0-4b87-a67e-4d5fde79cc7b",
            'title' => "Valentines Day",
            'date' => "2024-02-14",
            'description' => "Valentines Day Description",
            'location' => "Event Location",
        ]);

        foreach ($participantSeedList as $participant) {
            \DB::table('holiday_participant')->insert([
                                    'id' => Str::uuid()->toString(),
                                    'participant_id' => Participant::create($participant)->id,
                                    'holiday_id' => $holidayInstance->id,
                                ]);
        }






    }
}
