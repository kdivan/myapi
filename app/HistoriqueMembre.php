<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="HistoriqueMembre"),
 *  required={"id_historique","id_membre", "id_seance", "date"},
 *  @SWG\Property(property="id_historique",format="int64", type="integer", default=545),
 *  @SWG\Property(property="id_membre", type="integer"),
 *  @SWG\Property(property="id_seance", type="integer"),
 *  @SWG\Property(property="date", type="string", format="date-time"),
 *  @SWG\Property(property="membre", ref="#/definitions/Membre"),
 *)
 */
class HistoriqueMembre extends Model
{

    public $primaryKey = "id_historique";
    public $timestamps = false;
    public $table = "historique_membre";

    /**
     * @var array
     */
    protected $fillable = ['id_membre', 'id_seance', 'date'];

    public function membre()
    {
        return $this->belongsTo('App\Membre', 'id_membre');
    }

    public function seance()
    {
        return $this->belongsTo('App\Seance', 'id_seance');
    }
}
