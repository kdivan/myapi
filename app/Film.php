<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Swagger\Annotations\Xml;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Film"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_film", type="integer"),
 *  @SWG\Property(property="titre", type="string"),
 *  @SWG\Property(property="resum", type="string"),
 *  @SWG\Property(property="date_debut_affiche", type="string", format= "date"),
 *  @SWG\Property(property="date_fin_affiche", type="string", format="date"),
 *  @SWG\Property(property="duree_minutes", type="integer"),
 *  @SWG\Property(property="annee_production", type="integer"),
 *  @SWG\Property(property="genre", ref="#/definitions/Genre")
 *)
 */
class Film extends Model
{
    public $primaryKey = "id_film";
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function genre()
    {
        return $this->belongsTo('App\Genre', 'id_genre');
    }
}
