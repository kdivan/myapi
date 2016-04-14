<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $permission = ['ROLE_CRUD', 'ROLE_READONLY'];
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => Hash::make('secret'),
        'remember_token' => str_random(10),
        'role' => $permission[rand(0,sizeof($permission)-1)]
    ];
});

//Foctory forfait
$factory->define(App\Forfait::class, function (Faker\Generator $faker) {
    return [
        'nom' => $faker->name,
        'resum' => $faker->paragraph,
        'prix' => rand(1, 2500),
        'duree_jours' => rand(0, 50),
    ];
});

//Factory abonnement
$factory->define(App\Abonnement::class, function (Faker\Generator $faker) {
    return [
        'id_forfait' => rand(1, 20),
        'debut' => $faker->dateTimeThisMonth,
    ];
});

////Factory membres
$factory->define(App\Membre::class, function (Faker\Generator $faker) {
    return [
        'id_personne' => rand(1, 20),
        'id_abonnement' => $faker->email,
        'date_inscription' => $faker->dateTimeThisDecade,
        'date_abonnement' => $faker->dateTimeThisDecade,
    ];
});

////Factory historique_membre
$factory->define(App\HistoriqueMembre::class, function (Faker\Generator $faker) {
    return [
        'id_membre' => rand(1, 20),
        'id_seance' => rand(1, 20),
        'date' => $faker->dateTimeThisDecade,
    ];
});

//Factory seance
$factory->define(App\Seance::class, function (Faker\Generator $faker) {
    return [
        'id_film' => rand(1, 20),
        'id_salle' => rand(1, 20),
        'id_personne_ouvreur' => rand(1, 20),
        'id_personne_technicien' => rand(1, 20),
        'id_personne_menage' => rand(1, 20),
        'debut_seance' => $faker->dateTimeThisYear,
        'fin_seance' => $faker->dateTimeThisYear,
    ];
});

//Factory Redutions
$factory->define(App\Reduction::class, function (Faker\Generator $faker) {
    return [
        'nom' => $faker->numerify('reduction ##'),
        'date_debut' => $faker->dateTimeInInterval($startDate = '-1 days', $interval = '+ 1 hours'),
        'date_fin' => $faker->dateTimeInInterval($startDate = '-1 days', $interval = '+ 2 hours'),
        'pourcentage_reduction' => $faker->numberBetween($min = 0, $max = 100),
    ];
});
