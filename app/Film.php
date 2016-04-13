<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Film"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_film", type="integer", default=4554),
 *  @SWG\Property(property="titre", type="string"),
 *  @SWG\Property(property="resum", type="string"),
 *  @SWG\Property(property="date_debut_affiche", type="date", default="2011-01-12"),
 *  @SWG\Property(format="date", property="date_fin_affiche", type="date", default="2011-01-12"),
 *  @SWG\Property(property="duree_minutes", type="integer", default=180),
 *  @SWG\Property(property="annee_production", type="integer", default=2011),
 *)
 */
class Film extends Model
{
    public $primaryKey = "id_film";
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    public function genre()
    {
        return $this->belongsTo('App\Genre', 'id_genre');
    }
}
