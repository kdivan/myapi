<?php

use Illuminate\Database\Seeder;

class SeancesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit('Ohhh my god never do that.');
        }
        DB::table('seances')->truncate();
        factory(App\Seance::class, 20)->create();
    }
}
