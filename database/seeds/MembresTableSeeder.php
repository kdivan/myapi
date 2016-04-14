<?php

use Illuminate\Database\Seeder;
use \App\Membre;

class MembresTableSeeder extends Seeder
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
        Membre::truncate();
        factory(App\Membre::class, 20)->create();
    }
}
