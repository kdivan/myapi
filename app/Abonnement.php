<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    public $primaryKey = "id_abonnement";
    //Created_at et updated_at sont ajouté automatiquement mais on en a
    // pas besoin donc on disable
    public $timestamps = false;

    protected $fillable = ['id_forfait', 'debut'];

}
