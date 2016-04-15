<?php

namespace App\Http\Controllers;

use App\Abonnement;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
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
     *     description="This is to insert an abonnement. This can only be done by the logged in user.",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_forfait",
     *         in="formData",
     *         description="Enter the value of the forfait ID to assign with",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut",
     *         in="formData",
     *         description="Enter a starting date for the abonnement",
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
        $abonnements = Abonnement::create(Input::all());
        $abonnements->save();

        return response()->json(
            ['Abonnement' => $abonnements],
            201
        );
    }

    /**
     * @SWG\Get(path="/abonnement/{abonnementId}",
     *      tags={"abonnement"},
     *      summary="Show the requested abonnement",
     *      operationId="getAbonnementById",
     *      description="Show the requested abonnement (by ID)",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="abonnementId",
     *          description="ID of the abonnement that needs to be fetched",
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
     *         name="abonnementId",
     *         in="path",
     *         description="The id of the abonnement to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_forfait",
     *         in="formData",
     *         description="Enter the new value of id_forfait",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut",
     *         in="formData",
     *         description="Enter the new starting date",
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
     *   description="To delete an abonnement (by ID)",
     *   operationId="deleteAbonnement",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="abonnementId",
     *     in="path",
     *     description="ID of the abonnement you want to delete",
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

    /**
     * @SWG\Get(
     *     path="/abonnement/getNumberAboonnementByForfait",
     *     summary="Get number abonnement by forfait",
     *     tags={"abonnement"},
     *     description="return number of abonnement",
     *     operationId="getNumberAboonnementByForfait",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="forfaitId",
     *         in="path",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
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
     */
    public function getNumberAboonnementByForfait($forfaitId)
    {
        if (!is_numeric($forfaitId)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }
        $abonnements = Abonnement::where('id_forfait', '=', $forfaitId)
                            ->count();

        if (empty($abonnements)) {
            return response()->json(
                ['error' => 'there is no abonnement for this id'],
                404
            );
        }

        return response()->json(
            ['abonnements' => $abonnements],
            200
        );
    }
}
