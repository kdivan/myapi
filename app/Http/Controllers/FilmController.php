<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class FilmController extends Controller
{
    /**
     * @SWG\Get(path="/film",
     *   tags={"film"},
     *   operationId="getFilm",
     *   summary="Display a list of films.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Film")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $films = Film::all()->take(5);
        return $films;
    }

    /**
     * @SWG\Post(path="/film",
     *     tags={"film"},
     *     summary="add 1 film.",
     *     operationId="addFilm",
     *     description="This is to insert a film",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="titre",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_genre",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_distributeur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut_affiche",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin_affiche",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_minutes",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="annee_production",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
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
            'titre' => 'required|unique:films',
            'id_genre' => 'exists:genre,id_genre',
            'id_distribution' => 'exists:genre,id_genre',
            'date_debut_affiche' => 'date_format:Y-m-d',
            'date_fin_affiche' => 'date_format:Y-m-d',
            'duree_minutes' => 'integer',
            'annee_production' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $film = new Film();
        $film->titre = $request->titre;
        $film->resum = $request->resum;
        $film->id_genre = $request->id_genre;
        $film->id_distributeur = $request->id_distributeur;
        $film->date_debut_affiche = $request->date_debut_affiche;
        $film->date_fin_affiche = $request->date_fin_affiche;
        $film->duree_minutes = $request->duree_minutes;
        $film->annee_production = $request->annee_production;
        $film->save();
        return response()->json(
            ['Film' => $film],
            201
        );
    }

    /**
     * @SWG\Get(path="/film/{filmId}",
     *      tags={"film"},
     *      summary="show 1 row",
     *      operationId="getFilmById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="filmId",
     *          description="ID of film that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
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
        $films = Film::find($id);
        //Test si le film exist
        if (empty($films)) {
            return response()->json(
                ['error' => 'this film does not exist bitch'],
                404
            );
        }
        return $films;
    }

    /**
     * @SWG\Put(
     *     path="/film/{filmId}",
     *     tags={"film"},
     *     operationId="updateFilm",
     *     summary="Update an existing film",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="filmId",
     *         in="path",
     *         description="Pet object that needs to be added to the store",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="titre",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_genre",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_distributeur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut_affiche",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin_affiche",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_minutes",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="annee_production",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
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

        $films = Film::find($id);
        if (empty($films)) {
            return response()->json(
                ['error' => 'Film not found'],
                404
            );
        }
        $films->titre = $request->titre;
        $films->resum = $request->resum;
        $films->id_genre = $request->id_genre;
        $films->id_distributeur = $request->id_distributeur;
        $films->date_debut_affiche = $request->date_debut_affiche;
        $films->date_fin_affiche = $request->date_fin_affiche;
        $films->duree_minutes = $request->duree_minutes;
        $films->annee_production = $request->annee_production;
        $films->save();
    }

    /**
     * @SWG\Delete(path="/film/{filmId}",
     *   tags={"film"},
     *   summary="Delete purchase order by ID",
     *   description="For valid response try integer IDs with value < 1000. Anything above 1000 or nonintegers will generate API errors",
     *   operationId="deleteFilm",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="filmId",
     *     in="path",
     *     description="ID of the order that needs to be deleted",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="Order not found")
     * )
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
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
