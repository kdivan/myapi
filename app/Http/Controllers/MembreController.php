<?php

namespace App\Http\Controllers;

use App\Membre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class MembreController extends Controller
{
    /**
     * @SWG\Get(path="/membre",
     *   tags={"membre"},
     *   operationId="getMembre",
     *   summary="Display a list of membres.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Membre")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $membres = Membre::all()->take(5);
        return $membres;
    }

    /**
     * @SWG\POST(path="/membre",
     *     tags={"membre"},
     *     summary="add 1 membre",
     *     operationId="addMembre",
     *     description="This is to insert a membre",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_personne",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_abonnement",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="date_inscription",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="string",
     *         format="date-time",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_abonnement",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="string",
     *         format="date-time",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Membre")
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
            'id_personne' => 'required|exists:personnes',
            'id_abonnement' => 'required|exists:abonnements',
            'date_inscription' => 'required|date_format:Y-m-d H:i:s',
            'debut_abonnement' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $membres = Membre::create(Input::all());
        $membres->save();
        return response()->json(
            $membres,
            201
        );
    }

    /**
     * @SWG\Get(path="/membre/{membreId}",
     *      tags={"membre"},
     *      summary="show 1 row",
     *      operationId="getMembreById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="membreId",
     *          description="ID of membre that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Membre")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Membre not found"),
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
        $membres = Membre::find($id);
        //Test si le membre exist
        if (empty($membres)) {
            return response()->json(
                ['error' => 'this membre does not exist'],
                404
            );
        }
        return $membres;
    }

    /**
     * @SWG\Put(
     *     path="/membre/{membreId}",
     *     tags={"membre"},
     *     operationId="updateMembre",
     *     summary="Update an existing membre",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
    @SWG\Parameter(
     *         name="id_personne",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_abonnement",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="date_inscription",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_abonnement",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date",
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Membre not found",
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
        $validator = Validator::make($request->all(), [
            'id_personne' => 'required|exists:personnes',
            'id_abonnement' => 'required|exists:abonnements',
            'date_inscription' => 'required|date_format:Y-m-d H:i:s',
            'debut_abonnement' => 'required|date_format:Y-m-d H:i:s',
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

        $membres = Membre::find($id);
        if (empty($membres)) {
            return response()->json(
                ['error' => 'Membre not found'],
                404
            );
        }

        $membres->fill(Input::all());
        $membres->save();

        return response()->json(
            ['Membre' => $membres],
            201
        );
    }

    /**
     * @SWG\Delete(path="/membre/{membreId}",
     *   tags={"membre"},
     *   summary="Delete membre by ID",
     *   description="For valid response try integer IDs with value < 1000. Anything above 1000 or nonintegers will generate API errors",
     *   operationId="deleteMembre",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="membreId",
     *     in="path",
     *     description="ID of the membre that needs to be deleted",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="Membre not found")
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
        $membres = Membre::find($id);
        if (empty($membres)) {
            return response()->json(
                ['error' => 'there is no membre for this id'],
                404
            );
        }
        $membres->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
