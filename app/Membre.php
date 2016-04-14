<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Membre"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_membre", type="integer", default=4554),
 *  @SWG\Property(property="id_personne", type="integer"),
 *  @SWG\Property(property="id_abonnement", type="integer"),
 *  @SWG\Property(property="date_inscription", type="string", format="date"),
 *  @SWG\Property(property="debut_abonnement", type="string", format="date"),
 *)
 */

class Membre extends Model
{

    public $primaryKey = "id_membre";
    //Created_at et updated_at sont ajouté automatiquement mais on en a
    // pas besoin donc on disable
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id_personne', 'id_abonnement', 'date_inscription', 'debut_abonnement'];

}
