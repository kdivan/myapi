<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="HistoriqueMembre"),
 *  required={"id_historique","id_membre", "id_seance", "date"},
 *  @SWG\Property(format="int64", property="id_historique", type="integer", default=4554),
 *  @SWG\Property(property="id_membre", type="integer"),
 *  @SWG\Property(property="id_seance", type="integer"),
 *  @SWG\Property(property="date", type="datetime"),
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
}
