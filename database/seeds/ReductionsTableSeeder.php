<?php

use Illuminate\Database\Seeder;
use App\Reduction;

class ReductionsTableSeeder extends Seeder
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
        Reduction::truncate();
        factory(App\Reduction::class, 20)->create();
    }
}
