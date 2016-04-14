<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Salles"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_salle", type="integer"),
 *  @SWG\Property(property="numero_salle", type="integer"),
 *  @SWG\Property(property="nom_salle", type="string"),
 *  @SWG\Property(property="etage_salle", type="integer"),
 *  @SWG\Property(property="places", type="integer")
 *)
 */
class Salle extends Model
{
    public $primaryKey = 'id_salle';
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    protected $fillable = ['numero_salle', 'nom_salle', 'etage_salle', 'places'];
}
