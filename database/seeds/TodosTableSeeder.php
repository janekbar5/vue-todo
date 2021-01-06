<?php

use Illuminate\Database\Seeder;

class TodosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Todo::create(['title' => 'Buy Milk']);
        App\Models\Todo::create(['title' => 'Buy Bread']);
    }
}
