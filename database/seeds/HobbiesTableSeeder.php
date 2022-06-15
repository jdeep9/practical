<?php

use App\Hobby;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HobbiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Hobby::truncate();
        Schema::enableForeignKeyConstraints();

        Hobby::create(['name' => 'Sports']);
        Hobby::create(['name' => 'Movies']);
        Hobby::create(['name' => 'Music']);
        Hobby::create(['name' => 'Painting']);
    }
}
