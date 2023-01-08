<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 50; $i++) {
            Event::create([
                'name' => $faker->sentence,
                'event_table' => $faker->name,
                'publish_date' => $faker->date,
            ]);
        }
    }
}
