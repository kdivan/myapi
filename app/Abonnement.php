<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Abonnement"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_abonnement", type="integer", default=4554),
 *  @SWG\Property(property="id_forfait", type="integer"),
 *  @SWG\Property(property="debut", type="string", format="date-time"),
 *)
 */
class Abonnement extends Model
{
    public $primaryKey = 'id_abonnement';
    //Created_at et updated_at sont ajouté automatiquement mais on en a
    // pas besoin donc on disable
    public $timestamps = false;

    protected $fillable = ['id_forfait', 'debut'];

    public function forfait()
    {
        $this->belongsTo('App\Forfait', 'id_forfait');
    }

}
