<?php

namespace App\Http\Controllers;

use App\Genre;
use Illuminate\Http\Request;

use App\Http\Requests;

class GenreController extends Controller
{
    /**
     * @SWG\Get(path="/genre",
     *   tags={"genre"},
     *   operationId="getGenre",
     *   summary="Display a list of genres.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Genre")
     *     ),
     *   ),
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $genres = Genre::all()->take(5);
        return $genres;
    }

    /**
     * @SWG\Post(path="/genre",
     *     tags={"genre"},
     *     summary="add a new genre.",
     *     operationId="addGenre",
     *     description="This is to insert a new genre in the database",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Enter the name of the new genre",
     *         required=true,
     *         type="integer",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Genre")
     *          ),
     *      ),
     *   @SWG\Response(
     *       response=405,
     *       description="Invalid input",
     *   ),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|unique:genres',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $genre = new Genre();
        $genre->nom = $request->nom;
        $genre->save();
        return response()->json(
            ['id_genre' => $genre->id_genre],
            201
        );
    }

    /**
     * @SWG\Get(path="/genre/{genreId}",
     *      tags={"genre"},
     *      summary="show a genre",
     *      operationId="getGenreById",
     *      description="To show a genre with ID provided",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="genreId",
     *          description="ID of genre that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Genre")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Genre not found"),
     * )
     *
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
        $genre = Genre::find($id);
        //Test si le film exist
        if (empty($genre)) {
            return response()->json(
                ['error' => 'this film does not exist bitch'],
                404
            );
        }
        return $genre;
    }

    /**
     * @SWG\Put(
     *     path="/genre/{genreId}",
     *     tags={"genre"},
     *     operationId="updateGenre",
     *     summary="Update an existing genre",
     *     description="To update a genre with ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="genreId",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="New name of the genre",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Genre")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Genre not found",
     *     ),
     * )
     *
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * @SWG\Delete(path="/genre/{genreId}",
     *   tags={"genre"},
     *   summary="Delete a genre",
     *   operationId="deleteGenre",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="genreId",
     *     in="path",
     *     description="ID of the genre that needs to be deleted",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="Order not found")
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @SWG\Get(
     *     path="/genre/getFilmsForGenre/{id}",
     *     summary="Finds film for genre",
     *     tags={"genre"},
     *     description="return a list of film for genre",
     *     operationId="getFilmsForGenre",
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
    public function getFilmsForGenre($id)
    {
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }

        $genre = Genre::find($id);
        if (empty($genre)) {
            return response()->json(
                ['error' => 'genre not found'],
                404
            );
        }
        return $genre->films;

    }

}
