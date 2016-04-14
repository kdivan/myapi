<?php

use Illuminate\Database\Seeder;
use App\HistoriqueMembre;

class HistoriqueMembreTableSeeder extends Seeder
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
        HistoriqueMembre::truncate();
        factory(App\HistoriqueMembre::class, 5)->create();
    }
}
