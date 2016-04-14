<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    public $primaryKey = "id_seance";
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    public function film()
    {
        return $this->belongsTo('App\Film');
    }

    public function salles()
    {
        return $this->hasMany('App\Salle');
    }
}
