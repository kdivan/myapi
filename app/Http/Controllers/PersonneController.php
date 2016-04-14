<?php

namespace App\Http\Controllers;

use App\Personne;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class PersonneController extends Controller
{
    /**
     * @SWG\Get(path="/personne",
     *   tags={"personne"},
     *   operationId="getPersonne",
     *   summary="Display a list of Personne.",
     *   description="Diplaying a list of six personnes",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Personne")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $personne= Personne::all()->take(10);
        return $personne;
    }

    /**
     * @SWG\Post(path="/personne",
     *     tags={"personne"},
     *     summary="add 1 personne.",
     *     operationId="addPersonne",
     *     description="This is to insert a personne",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Personne's lastname you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="prenom",
     *         in="formData",
     *         description="Personne's firstname you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_naissance",
     *         in="formData",
     *         description="Personne's birthday you want to update",
     *         required=true,
     *         format="date",
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Personne's email you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="adresse",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cpostal",
     *         in="formData",
     *         description="Personne's code postal you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="ville",
     *         in="formData",
     *         description="Personne's ville you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pays",
     *         in="formData",
     *         description="Personne's country you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
     *          ),
     *      ),
     *   @SWG\Response(
     *       response=405,
     *       description="Invalid input",
     *   ),
     * )
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //We make all field required except the adress
        $validator = Validator::make($request->all(), [
            'nom' => 'required',
            'prenom' => 'required',
            'date_naissance' => 'required|date_format:Y-m-d',
            'email' => 'required|email',
            'cpostal' => 'required',
            'ville' => 'required',
            'pays' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $personne = Personne::create(Input::all());
        $personne->save();
        return response()->json(
            ['Personne' => $personne],
            201
        );
    }

    /**
     * @SWG\Get(path="/personne/{personneId}",
     *      tags={"personne"},
     *      summary="show 1 row",
     *      operationId="getPersonneById",
     *      description="Show one Personne",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="personneId",
     *          description="ID of personne that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer",
     *          format="int64"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Personne not found"),
     * )
     * Display the specified resource.
     *
     * @param  int  $id
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
        $personne = Personne::find($id);
        //Test si la personne exist
        if (empty($personne)) {
            return response()->json(
                ['error' => 'this personne does not exist'],
                404
            );
        }
        return $personne;
    }

    /**
     * @SWG\Put(
     *     path="/personne/{personneId}",
     *     tags={"personne"},
     *     operationId="updatePersonne",
     *     summary="Update an existing personne",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="personneId",
     *         in="path",
     *         description="Personne object that needs to be added to the store",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Personne's lastname you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="prenom",
     *         in="formData",
     *         description="Personne's firstname you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_naissance",
     *         in="formData",
     *         description="Personne's birthday you want to update",
     *         required=false,
     *         format="date",
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         description="Personne's email you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="adresse",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cpostal",
     *         in="formData",
     *         description="Personne's code postal you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="ville",
     *         in="formData",
     *         description="Personne's ville you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pays",
     *         in="formData",
     *         description="Personne's country you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Personne")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Personne not found",
     *     ),
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!is_numeric($id)) {
            return response()->json(
                ['error' => 'Invalid ID supplied'],
                400
            );
        }

        $personne = Personne::find($id);
        if (empty($personne)) {
            return response()->json(
                ['error' => 'Personne not found'],
                404
            );
        }

        $personne->fill(Input::all());
        $personne->save();
        return response()->json(
            ['Fields have correctly update'],
            200
        );
    }

    /**
     * @SWG\Delete(path="/personne/{personneId}",
     *   tags={"personne"},
     *   summary="Delete purchase order by ID",
     *   description="For valid response try integer IDs with value < 1000. Anything above 1000 or nonintegers will generate API errors",
     *   operationId="deletePersonne",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="personneId",
     *     in="path",
     *     description="ID of the order that needs to be deleted",
     *     required=true,
     *     type="string"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="Order not found")
     * )
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
        $personne = Personne::find($id);
        if (empty($personne)) {
            return response()->json(
                ['error' => 'there is no personne for this id'],
                404
            );
        }
        $personne->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
