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
     *     description="This is to insert a new salle in the database",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="numero_salle",
     *         in="formData",
     *         description="Enter the number of the room",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="nom_salle",
     *         in="formData",
     *         description="Enter the name of the room",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="etage_salle",
     *         in="formData",
     *         description="Enter the floor of the room",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="places",
     *         in="formData",
     *         description="Enter the capacity of the room",
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
     *      summary="Show a salle",
     *      operationId="getSalleById",
     *      description="Show a salle with ID provided",
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
     *     operationId="update an existing seance",
     *     summary="To update an existing seance with ID provided",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *      @SWG\Parameter(
     *         name="numero_salle",
     *         in="formData",
     *         description="New number of the room",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="nom_salle",
     *         in="formData",
     *         description="New name of the room",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="etage_salle",
     *         in="formData",
     *         description="New floor of the room",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="places",
     *         in="formData",
     *         description="New capacity of the room",
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
            'numero_salle' => 'integer|unique:salles',
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

        $salle->fill(Input::all());
        $salle->save();

        return response()->json(
            ['Salle' => $salle],
            Response::HTTP_OK
        );

    }

    /**
     * @SWG\Delete(path="/salle/{salleId}",
     *   tags={"salle"},
     *   summary="Delete salled",
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
                ['error' => 'there is no salle for this id'],
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
