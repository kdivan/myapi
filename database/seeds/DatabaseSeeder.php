<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (App::environment() === 'production') {
            exit('I just stopped you getting fired.');
        }
        $this->call(UsersTableSeeder::class);
        $this->call(ReductionsTableSeeder::class);
        $this->call(ForfaitsTableSeeder::class);
        $this->call(AbonnementsTableSeeder::class);

        $this->call(HistoriqueMembreTableSeeder::class);

        //$this->call(MembresTableSeeder::class);
        $this->call(SeancesTableSeeder::class);
    }
}
