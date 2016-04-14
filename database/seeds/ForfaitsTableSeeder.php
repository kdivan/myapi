<?php

use Illuminate\Database\Seeder;
use App\Forfait;

class ForfaitsTableSeeder extends Seeder
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
        Forfait::truncate();
        factory(App\Forfait::class, 20)->create();
    }
}
