<?php

namespace App\Http\Controllers;

use App\Abonnement;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;


class AbonnementController extends Controller
{
    /**
     * @SWG\Get(path="/abonnement",
     *   tags={"abonnement"},
     *   operationId="getAbonnement",
     *   summary="Display a list of abonnements.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Abonnement")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abonnements = Abonnement::all()->take(5);
        return $abonnements;
    }

    /**
     * @SWG\Post(path="/abonnement",
     *     tags={"abonnement"},
     *     summary="add 1 abonnement",
     *     operationId="addAbonnement",
     *     description="This is to insert an abonnement",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_forfait",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Abonnement")
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
            'id_forfait' => 'required|exists:forfaits,id_forfait',
            'debut' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $abonnements = Abonnement::create($request);
        $abonnements->save();

        return response()->json(
            ['Abonnement' => $abonnements],
            201
        );
    }

    /**
     * @SWG\Get(path="/abonnement/{abonnementId}",
     *      tags={"abonnement"},
     *      summary="show 1 row",
     *      operationId="getAbonnementById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="abonnementId",
     *          description="ID of abonnement that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Abonnement")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Abonnement not found"),
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
        $abonnements = Abonnement::find($id);
        //Test si le abonnement exist
        if (empty($abonnements)) {
            return response()->json(
                ['error' => 'this abonnement does not exist'],
                404
            );
        }
        return $abonnements;
    }

    /**
     * @SWG\Put(
     *     path="/abonnement/{abonnementId}",
     *     tags={"abonnement"},
     *     operationId="updateAbonnement",
     *     summary="Update an existing abonnement",
     *     description="Updating an exiting abonnement with an ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_forfait",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *         format="date",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Abonnement")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Abonnement not found",
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
            'id_forfait' => 'required|exists:forfaits,id_forfait',
            'debut' => 'required|date_format:Y-m-d H:i:s',
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

        $abonnements = Abonnement::find($id);
        if (empty($abonnements)) {
            return response()->json(
                ['error' => 'Abonnement not found'],
                404
            );
        }
        $abonnements->fill(Input::all());
        $abonnements->save();

        return response()->json(
            ['Abonnement' => $abonnements],
            201
        );
    }

    /**
     * @SWG\Delete(path="/abonnement/{abonnementId}",
     *   tags={"abonnement"},
     *   summary="Delete abonnement by ID",
     *   description="For valid response try integer IDs with value < 1000. Anything above 1000 or nonintegers will generate API errors",
     *   operationId="deleteAbonnement",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="abonnementId",
     *     in="path",
     *     description="ID of the abonnement that needs to be deleted",
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
        $abonnements = Abonnement::find($id);
        if (empty($abonnements)) {
            return response()->json(
                ['error' => 'there is no abonnement for this id'],
                404
            );
        }
        $abonnements->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
