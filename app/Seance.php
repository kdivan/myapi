<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Seances"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id", type="integer"),
 *  @SWG\Property(format="int64", property="id_film", type="integer"),
 *  @SWG\Property(format="int64", property="id_salle", type="integer"),
 *  @SWG\Property(format="int64", property="id_personne_ouvreur", type="integer"),
 *  @SWG\Property(format="int64", property="id_personne_technicien", type="integer"),
 *  @SWG\Property(format="int64", property="id_personne_menage", type="integer"),
 *  @SWG\Property(property="debut_seance", type="date", default="2011-01-12"),
 *  @SWG\Property(format="date", property="fin_seance", type="date", default="2011-01-12"),
 *)
 */
class Seance extends Model
{
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    protected $fillable = ['id_film', 'id_salle', 'id_personne_ouvreur', 'id_personne_technicien',
                            'id_personne_menage', 'debut_seance', 'fin_seance'];

    public function film()
    {
        return $this->belongsTo('App\Film');
    }

    public function salle()
    {
        return $this->belongsTo('App\Salle');
    }
}
