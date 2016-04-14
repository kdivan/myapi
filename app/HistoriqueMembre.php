<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoriqueMembre extends Model
{
    /**
     * @SWG\Definition(
     *  @SWG\Xml(name="HistoriqueMembre"),
     *  required={"id_historique","id_membre", "id_seance"},
     *  @SWG\Property(format="int64", property="id_historique", type="integer", default=4554),
     *  @SWG\Property(property="id_membre", type="integer"),
     *  @SWG\Property(property="id_seance", type="integer"),
     *)
     */

    public $primaryKey = "id_historique";
    public $timestamps = false;
}
