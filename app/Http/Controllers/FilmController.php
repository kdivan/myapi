<?php

namespace App\Http\Controllers;

use App\Film;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
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
     *              *     @SWG\Parameter(
     *         description="Force l'affichage de tous les éléments.",
     *         in="query",
     *         name="all",
     *         type="boolean"
     *     ),
     *     @SWG\Parameter(
     *         description="Le nombre d'éléments à afficher",
     *         in="query",
     *         name="nb_element",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="La page à afficher",
     *         in="query",
     *         name="page",
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="filtre par genre",
     *         in="query",
     *         name="id_genre",
     *         type="integer"
     *     ),
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
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'all' => 'string',
            'nb_element' => 'numeric',
            'id_genre' => 'numeric|exists:genres',
            'id_distributeur' => 'numeric|exists:distributeurs'
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422); // HTTP Status code
        }

        // On check si on doit tout afficher.
        if (!empty($request->all) && $request->all === 'true')
        {
            $films = Film::all();
        }
        else
        {
            // Afficher 15 elements par défaut.

            $nb_element = 15;

            if (!empty($request->nb_element))
            {
                $nb_element = $request->nb_element;
            }

            $films = Film::paginate($nb_element);
        }

        if (!empty($request->id_genre))
        {
            $id_genre = (int) $request->id_genre;
            $films = $films->where('id_genre', $id_genre);
        }

        if (!empty($request->id_distributeur))
        {
            $id_distributeur = (int) $request->id_distributeur;
            $films = $films->where('id_distributeur', $id_distributeur);
        }

        if ($films->count() === 0)
        {
            return response()->json([],204);
        }

        return $films;
    }

    /**
     * @SWG\Post(path="/film",
     *     tags={"film"},
     *     summary="To add a new film.",
     *     operationId="addFilm",
     *     description="This is to insert a new film in the database.",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="titre",
     *         in="formData",
     *         description="Enter the name of the film",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_genre",
     *         in="formData",
     *         description="Enter the id_genre of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_distributeur",
     *         in="formData",
     *         description="Enter the id_distributeur of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="Enter the summary of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut_affiche",
     *         in="formData",
     *         description="Enter the opening date of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin_affiche",
     *         in="formData",
     *         description="Enter the ending date of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_minutes",
     *         in="formData",
     *         description="Enter the duration of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="annee_production",
     *         in="formData",
     *         description="Enter the year of production of the film",
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
            'id_genre' => 'exists:genres,id_genre',
            'id_distributeur' => 'exists:distributeurs,id_distributeur',
            'date_debut_affiche' => 'date_format:Y-m-d|before:date_fin_affiche',
            'date_fin_affiche' => 'date_format:Y-m-d|after:date_debut_affiche',
            'duree_minutes' => 'integer',
            'annee_production' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $film = Film::create(Input::all());
        $film->save();
        return response()->json(
            ['Film' => $film],
            201
        );
    }

    /**
     * @SWG\Get(path="/film/{filmId}",
     *      tags={"film"},
     *      summary="show the requested film",
     *      operationId="getFilmById",
     *      description="To show information about the requested film",
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
        $film = Film::find($id);
        //Test si le film exist
        if (empty($film)) {
            return response()->json(
                ['error' => 'this film does not exist'],
                404
            );
        }
        return $film;
    }

    /**
     * @SWG\Put(
     *     path="/film/{filmId}",
     *     tags={"film"},
     *     operationId="updateFilm",
     *     summary="Update an existing film",
     *     description="To Update a film with an ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="filmId",
     *         in="path",
     *         description="Enter the id of the film to update",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="titre",
     *         in="formData",
     *         description="New name of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_genre",
     *         in="formData",
     *         description="New genre of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_distributeur",
     *         in="formData",
     *         description="New distributeur of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="New summary of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut_affiche",
     *         in="formData",
     *         description="New starting date of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin_affiche",
     *         in="formData",
     *         description="New ending date of the film",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_minutes",
     *         in="formData",
     *         description="New duration of the film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="annee_production",
     *         in="formData",
     *         description="New production year of the film",
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
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Validation des parametres a sauvegarder
        $validator = Validator::make($request->all(), [
            'titre' => 'string',
            'id_genre' => 'exists:genres,id_genre',
            'id_distributeur' => 'exists:distributeurs,id_distributeur',
            'date_debut_affiche' => 'date_format:Y-m-d|before:date_fin_affiche',
            'date_fin_affiche' => 'date_format:Y-m-d|after:date_debut_affiche',
            'duree_minutes' => 'integer',
            'annee_production' => 'integer',
        ]);
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }
        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $film = Film::find($id);
        if (empty($film)) {
            return response()->json(
                ['error' => 'Film not found'],
                404
            );
        }

        $film->fill(Input::all());
        $film->save();
        return response()->json(
            ['Film' => $film],
            200
        );
    }

    /**
     * @SWG\Delete(path="/film/{filmId}",
     *   tags={"film"},
     *   summary="To delete a Film",
     *   description="Delete a film with the ID provided",
     *   operationId="deleteFilm",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="filmId",
     *     in="path",
     *     description="ID of the film that needs to be deleted",
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
        $film = Film::find($id);
        if (empty($film)) {
            return response()->json(
                ['error' => 'there is no film for this id'],
                404
            );
        }
        $film->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }

    /**
     * @SWG\Get(
     *     path="/film/getFilmWithGenre/{id}",
     *     summary="Finds film with genre",
     *     tags={"film"},
     *     description="return a film with its genre",
     *     operationId="getFilmWithGenre",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Film")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid Id supplied",
     *     ),
     *      @SWG\Response(
     *         response="404",
     *         description="genre not found",
     *     ),
     * )
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
