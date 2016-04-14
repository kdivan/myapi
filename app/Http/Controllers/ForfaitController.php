<?php

namespace App\Http\Controllers;

use App\Forfait;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class ForfaitController extends Controller
{
    /**
     * @SWG\Get(path="/forfait",
     *   tags={"forfait"},
     *   operationId="getForfait",
     *   summary="Display a list of forfait.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Forfait")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forfaits = Forfait::all()->take(5);
        return $forfaits;
    }

    /**
     * @SWG\Post(path="/forfait",
     *     tags={"forfait"},
     *     summary="add a new forfait",
     *     operationId="addForfait",
     *     description="This is to insert a new forfait ine the database",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Enter the name of the forfait",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="Enter the summary of the forfait",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="prix",
     *         in="formData",
     *         description="Enter the cost of the forfait",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_jours",
     *         in="formData",
     *         description="Enter the duration of the forfait",
     *         required=false,
     *         type="integer",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Forfait")
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
            'nom' => 'required|unique:forfaits',
            'resum' => 'required|string',
            'prix' => 'integer|min:0',
            'duree_jours' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $forfait = Forfait::create(Input::all());
        $forfait->save();

        return response()->json(
            ['Forfait' => $forfait],
            201
        );
    }

    /**
     * @SWG\Get(path="/forfait/{forfaitId}",
     *      tags={"forfait"},
     *      summary="show a forfait",
     *      operationId="getForfaitById",
     *      description="Show the forfait with ID provided",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="forfaitId",
     *          description="ID of forfait that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Forfait")
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
        $forfaits = Forfait::find($id);
        //Test si le forfait exist
        if (empty($forfaits)) {
            return response()->json(
                ['error' => 'this forfait does not exist'],
                404
            );
        }
        return $forfaits;
    }

    /**
     * @SWG\Put(
     *     path="/forfait/{forfaitId}",
     *     tags={"forfait"},
     *     operationId="updateForfait",
     *     summary="To update a forfait",
     *     description="Update an existing forfait with ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="New name of the forfait",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="resum",
     *         in="formData",
     *         description="New summary of the forfait",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="prix",
     *         in="formData",
     *         description="New price of the forfait",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="duree_jours",
     *         in="formData",
     *         description="New duration of the forfait",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Forfait")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Forfait not found",
     *     ),
     *     security={{"petstore_auth":{"write:forfaits", "read:forfaits"}}}
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
            'nom' => 'unique:forfaits',
            'resum' => 'string',
            'prix' => 'integer|min:0',
            'duree_jours' => 'integer|min:0',
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

        $forfait = Forfait::find($id);
        if (empty($forfait)) {
            return response()->json(
                ['error' => 'Forfait not found'],
                404
            );
        }

        $forfait->fill(Input::all());
        $forfait->save();

        return response()->json(
            ['Forfait' => $forfait],
            201
        );
    }

    /**
     * @SWG\Delete(path="/forfait/{forfaitId}",
     *   tags={"forfait"},
     *   summary="Delete a forfait",
     *   description="To delete a forfait with ID orderer",
     *   operationId="deleteForfait",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="forfaitId",
     *     in="path",
     *     description="ID of the forfait that needs to be deleted",
     *     required=true,
     *     type="integer"
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
        $forfaits = Forfait::find($id);
        if (empty($forfaits)) {
            return response()->json(
                ['error' => 'there is no forfait for this id'],
                404
            );
        }
        $forfaits->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }

}
