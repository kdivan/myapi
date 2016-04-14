<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
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
        User::truncate();
        factory(App\User::class, 20)->create();
    }
}
