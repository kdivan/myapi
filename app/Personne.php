<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Personne"),
 *  required={"id_personne","nom", "prenom", "date_naissance", "email", "cpostal", "ville", "pays"},
 *  @SWG\Property(property="id_personne", format="int64", type="integer"),
 *  @SWG\Property(property="nom", type="string"),
 *  @SWG\Property(property="prenom", type="string"),
 *  @SWG\Property(property="date_naissance", type="string", format= "date"),
 *  @SWG\Property(property="email", type="string"),
 *  @SWG\Property(property="adresse", type="string"),
 *  @SWG\Property(property="cpostal", type="string"),
 *  @SWG\Property(property="ville", type="string"),
 *  @SWG\Property(property="pays", type="string"),
 *)
 */

class Personne extends Model
{
    public $primaryKey = "id_personne";
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ["id_personne","nom", "prenom", "date_naissance", "email", "cpostal", "ville", "pays"];

    public function fonctions()
    {
        return $this->belongsToMany("App\Fonction","employes","id_personne","id_fonction");
    }
}
