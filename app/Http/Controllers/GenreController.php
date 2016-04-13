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
     * @SWG\POST(path="/genre",
     *     tags={"genre"},
     *     summary="add 1 film.",
     *     operationId="addGenre",
     *     description="This is to insert a genre",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="the name of the field you want to update",
     *         required=true,
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
            ['id_film' => $genre->id_film],
            201
        );
    }

    /**
     * @SWG\Get(path="/genre/{genreId}",
     *      tags={"genre"},
     *      summary="show 1 row",
     *      operationId="getGenreById",
     *      description="Show one row",
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
     *      @SWG\Response(response=404, description="Film not found"),
     * )
     *
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * @SWG\Put(
     *     path="/film/{genreId}",
     *     tags={"genre"},
     *     operationId="updateGenre",
     *     summary="Update an existing genre",
     *     description="",
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
     *   summary="Delete genre by ID",
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
