<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forfait extends Model
{
    public $primaryKey = "id_forfait";
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

}
