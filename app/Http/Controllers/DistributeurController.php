<?php

namespace App\Http\Controllers;

use App\Distributeur;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class DistributeurController extends Controller
{

    /**
     * @SWG\Get(path="/distributeur",
     *   tags={"distributeur"},
     *   operationId="getDistributeur",
     *   summary="Display a list of distributeurs.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Distributeur")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributeurs = Distributeur::all();
        //->take(5)
        return $distributeurs;
    }


    /**
     * @SWG\Post(path="/distributeur",
     *     tags={"distributeur"},
     *     summary="add 1 distributeur.",
     *     operationId="addDistributeur",
     *     description="This is to insert a distributor",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="the field name you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="telephone",
     *         in="formData",
     *         description="the field telephone you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="adresse",
     *         in="formData",
     *         description="the field adresse you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cpostal",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="ville",
     *         in="formData",
     *         description="the field ville you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pays",
     *         in="formData",
     *         description="the field pays you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
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
            'nom' => 'required|unique:nom',
            'telephone' => 'required',
            'adresse' => 'string',
            'cpostal' => 'integer',
            'ville' => 'string',
            'pays' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $distributeur = Distributeur::create($request);
        $distributeur->save();
        return response()->json(
            ['Distributeur' => $distributeur],
            201
        );
    }

    /**
     * @SWG\Get(path="/distributeur/{distributeurId}",
     *      tags={"distributeur"},
     *      summary="show 1 row",
     *      operationId="getDistributeurById",
     *      description="Show one row of distributor",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="distributeurId",
     *          description="ID of distributor that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Distributeur not found"),
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
        $distributeur = Distributeur::find($id);
        //Test si le film exist
        if (empty($distributeur)) {
            return response()->json(
                ['error' => 'this distributeur does not exist !'],
                404
            );
        }
        return $distributeur;
    }


    /**
     * @SWG\Put(
     *     path="/distributeur/{distributeurId}",
     *     tags={"distributeur"},
     *     operationId="updateDistributeur",
     *     summary="Update an existing distributeur",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="distributeurId",
     *         in="path",
     *         description="Update the information of a distributeur",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="the field nom you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="telephone",
     *         in="formData",
     *         description="the field telephone you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="adresse",
     *         in="formData",
     *         description="the field adresse you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cpostal",
     *         in="formData",
     *         description="the field cpostal you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="ville",
     *         in="formData",
     *         description="the field ville you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pays",
     *         in="formData",
     *         description="the field pays you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Distributeur")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Distributeur not found",
     *     ),
     *     security={{"petstore_auth":{"write:distributeur", "read:distributeur"}}}
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
            'nom' => 'string',
            'telephone' => 'string',
            'adresse' => 'string',
            'cpostal' => 'integer',
            'ville' => 'string',
            'pays' => 'string',
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

        $distributeurs = Distributeur::find($id);
        if (empty($distributeurs)) {
            return response()->json(
                ['error' => 'distributeur not found'],
                404
            );
        }

        $distributeurs->fill(Input::all());
        $distributeurs->save();

        return response()->json(
            ['message' => "distributeur has been updated successfully"],
            200
        );

    }

    /**
     * @SWG\Delete(path="/distributeur/{distributeurId}",
     *   tags={"distributeur"},
     *   summary="Delete distributeur order by ID",
     *   description="Delete a distributeur with his ID",
     *   operationId="deleteDistributeur",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="distributeurId",
     *     in="path",
     *     description="ID of the distributeur that needs to be deleted",
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
        $distributeurs = Distributeur::find($id);
        if (empty($distributeurs)) {
            return response()->json(
                ['error' => 'there is no distributeur for this id'],
                404
            );
        }
        $distributeurs->delete();
        return response()->json(
            ['message' => "distributeur resource deleted successfully"],
            200
        );
    }

}
