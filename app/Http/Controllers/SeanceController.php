<?php

namespace App\Http\Controllers;

use App\Seance;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class SeanceController extends Controller
{
    /**
     * @SWG\Get(path="/seance",
     *   tags={"seance"},
     *   operationId="getSeance",
     *   summary="Display a list of seances.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Seance")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seances = Seance::all();
        return $seances;
    }

    /**
     * @SWG\Post(path="/seance",
     *     tags={"seance"},
     *     summary="Add a new seance.",
     *     operationId="addSeance",
     *     description="This is to insert a film",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_ouvreur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_technicien",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_menage",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date-time",
     *     ),
     *     @SWG\Parameter(
     *         name="fin_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date-time",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *      ),
     *   @SWG\Response(
     *       response=405,
     *       description="Invalid input",
     *   ),
     * )
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //Validation des parametres a sauvegarder
        $validator = Validator::make($request->all(), [
            'id_film' => 'exists:film,id_film',
            'id_salle' => 'exists:salle,id_salle',
            'id_personne_ouvreur' => 'exists:personne,id_personne',
            'id_personne_technicien' => 'exists:personne,id_personne',
            'id_personne_menage' => 'exists:personne,id_personne',
            'debut_seance' => 'date',
            'fin_seance' => 'date',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $seance = new Seance();
        $seance->id_film = $request->id_film;
        $seance->id_salle = $request->id_salle;
        $seance->id_personne_ouvreur = $request->id_personne_ouvreur;
        $seance->id_personne_technicien = $request->id_personne_technicien;
        $seance->id_personne_menage = $request->id_personne_menage;
        $seance->debut_seance = $request->debut_seance;
        $seance->fin_seance = $request->fin_seance;
        $seance->save();
        return response()->json(
            ['Seance' => $seance],
            201
        );
    }

    /**
     * @SWG\Get(path="/seance/{seanceId}",
     *      tags={"seance"},
     *      summary="Show 1 row",
     *      operationId="getSeanceById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="seanceId",
     *          description="ID of seance that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Film not found"),
     * )
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //On test le format d'entrée de l'ID
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }
        $seance = Seance::find($id);
        //Test si le film exist
        if (empty($seance)) {
            return response()->json(
                ['error' => 'this film does not exist bitch'],
                404
            );
        }
        return $seance;
    }

    /**
     * @SWG\Put(
     *     path="/seance/{seanceId}",
     *     tags={"seance"},
     *     operationId="update seance",
     *     summary="Update an existing seance",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="seanceId",
     *         in="path",
     *         description="Seance object",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_ouvreur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_technicien",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_menage",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="fin_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Film not found",
     *     ),
     *     security={{"petstore_auth":{"write:films", "read:films"}}}
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }

        $seance = Film::find($id);
        if (empty($seance)) {
            return response()->json(
                ['error' => 'Film not found'],
                404
            );
        }
        $seance->titre = $request->titre;
        $seance->resum = $request->resum;
        $seance->id_genre = $request->id_genre;
        $seance->id_distributeur = $request->id_distributeur;
        $films->date_debut_affiche = $request->date_debut_affiche;
        $films->date_fin_affiche = $request->date_fin_affiche;
        $films->duree_minutes = $request->duree_minutes;
        $films->annee_production = $request->annee_production;
        $films->save();
    }


    public function destroy($id)
    {
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }
        $films = Film::find($id);
        if (empty($films)) {
            return response()->json(
                ['error' => 'there is no film for this id'],
                404
            );
        }
        $films->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }

    /**
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getFilmWithGenre($id)
    {
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }

        $films = Film::find($id);
        if (empty($films)) {
            return response()->json(
                ['error' => 'genre not found'],
                404
            );
        }

        return $films->genre;
    }
}
