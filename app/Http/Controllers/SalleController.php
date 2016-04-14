<?php

namespace App\Http\Controllers;

use App\Salle;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class SalleController extends Controller
{
    /**
     * @SWG\Get(path="/salle",
     *   tags={"salle"},
     *   operationId="getSalle",
     *   summary="Display a list of salles.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Salle")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salles = Salle::all();
        return $salles;
    }


    /**
     * @SWG\Post(path="/salle",
     *     tags={"salle"},
     *     summary="Add a new salle.",
     *     operationId="addSalle",
     *     description="This is to insert a salle",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="numero_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="nom_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="etage_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="places",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
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
            'numero_salle' => 'required|integer|unique:salles',
            'nom_salle' => 'required|string',
            'etage_salle' => 'required|integer',
            'places' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $salle = Salle::create(Input::all());
        $salle->save();

        return response()->json(
            ['Salle' => $salle],
            Response::HTTP_CREATED
        );
    }

    /**
     * @SWG\Get(path="/salle/{salleId}",
     *      tags={"salle"},
     *      summary="Show 1 row",
     *      operationId="getSalleById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="salleId",
     *          description="ID of salle that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Salle")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Salle not found"),
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
        $salle = Salle::find($id);
        //Test si le film exist
        if (empty($salle)) {
            return response()->json(
                ['error' => 'this seance does not exist'],
                404
            );
        }
        return $salle;
    }


    /**
     * @SWG\Put(
     *     path="/salle/{salleId}",
     *     tags={"salle"},
     *     operationId="update seance",
     *     summary="Update an existing seance",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *         name="numero_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="nom_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="etage_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="places",
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
     *              @SWG\Items(ref="#/definitions/Salle")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Salle not found",
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
            'numero_salle' => 'integer|unique',
            'nom_salle' => 'string',
            'etage_salle' => 'integer',
            'places' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }

        $salle = Salle::find($id);
        if (empty($salle)) {
            return response()->json(
                ['error' => 'Salle not found'],
                404
            );
        }

        $salle->fill(Salle::all());
        $salle->save();

        return response()->json(
            ['Fields have been correctly update'],
            Response::HTTP_OK
        );

    }

    /**
     * @SWG\Delete(path="/salle/{salleId}",
     *   tags={"salle"},
     *   summary="Delete salle by id",
     *   operationId="deleteSeance",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="salleId",
     *     in="path",
     *     description="ID of the reduction that needs to be deleted",
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
        $salle = Salle::find($id);
        if (empty($salle)) {
            return response()->json(
                ['error' => 'there is no film for this id'],
                404
            );
        }
        $salle->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
