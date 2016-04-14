<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Fonction"),
 *  required={"id_fonction","nom", "salaire", "cadre"},
 *  @SWG\Property(format="int64", property="id_fonction", type="integer", default=1),
 *  @SWG\Property(property="nom", type="string"),
 *  @SWG\Property(property="salaire", type="integer"),
 *  @SWG\Property(property="cadre", type="boolean"),
 *)
 */
class Fonction extends Model
{
    public $primaryKey = "id_fonction";
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['nom', 'salaire', 'cadre'];

    /**
     * @return mixed
     */
    public function personnes()
    {
        return $this->belongsToMany('App\Personne', 'employes', 'id_personne', 'id_fonction');
    }
}
