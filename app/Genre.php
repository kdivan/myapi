<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @SWG\Definition(
 *  @SWG\Xml(name="Genre"),
 *  required=true,
 *  @SWG\Property(format="int64", property="id_film", type="integer", default=4554),
 *  @SWG\Property(property="titre", type="string",default="Erotic"),
 *)
 */

class Genre extends Model
{
    public $primaryKey = "id_genre";
    //Created_at et updated_at sont ajouté automatiquement mais on en as
    // pas besoin donc on disable
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function films()
    {
        return $this->hasMany('App\Film','id_genre');
    }
}
