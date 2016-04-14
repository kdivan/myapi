<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Distributeur"),
 *  required={"id_distributeur","nom", "telephone"},
 *  @SWG\Property(format="int64", property="id_distributeur", type="integer", default=4554),
 *  @SWG\Property(property="nom", type="string"),
 *  @SWG\Property(property="telephone", type="string"),
 *  @SWG\Property(property="adresse", type="string"),
 *  @SWG\Property(property="cpostal", type="integer"),
 *  @SWG\Property(property="ville", type="string"),
 *  @SWG\Property(property="pays", type="string"),
 *)
 */

class Distributeur extends Model
{
    public $primaryKey = "id_distributeur";
    public $timestamps = false;
    /**
     * @var array
     */
    protected $fillable = ['nom', 'telephone', 'adresse', 'cpostal', 'ville', 'pays'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function film() {
        return $this->hasMany('App\Film', 'id_film');
    }

}
