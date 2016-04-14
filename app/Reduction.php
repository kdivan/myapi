<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reduction extends Model
{
    public $primaryKey = "id_reduction";
    //Created_at et updated_at sont ajouté automatiquement mais on en a
    // pas besoin donc on disable
    public $timestamps = false;

}
