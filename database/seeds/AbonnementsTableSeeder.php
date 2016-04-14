<?php

use Illuminate\Database\Seeder;
use \App\Abonnement;

class AbonnementsTableSeeder extends Seeder
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
        Abonnement::truncate();
        factory(App\Abonnement::class, 20)->create();
    }
}
