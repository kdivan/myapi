<?php

namespace App\Http\Controllers;

use App\Fonction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class FonctionController extends Controller
{
    /**
     * @SWG\Get(path="/fonction",
     *   tags={"fonction"},
     *   operationId="getFonction",
     *   summary="Display a list of fonctions.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Fonction")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fonctions = Fonction::all();
        //->take(5)
        return $fonctions;
    }


    /**
     * @SWG\Post(path="/fonction",
     *     tags={"fonction"},
     *     summary="add 1 fonction.",
     *     operationId="addFonction",
     *     description="This is to insert a fonction",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="the field nom you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="salaire",
     *         in="formData",
     *         description="the field salaire you want to update",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cadre",
     *         in="formData",
     *         description="the field cadre you want to update",
     *         required=true,
     *         type="boolean",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Fonction")
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
            'nom' => 'required|unique:fonctions',
            'salaire' => 'required',
            'cadre' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $fonction = Fonction::create(Input::all());
        $fonction->save();
        return response()->json(
            ['Fonction' => $fonction],
            201
        );
    }

    /**
     * @SWG\Get(path="/fonction/{fonctionId}",
     *      tags={"fonction"},
     *      summary="show 1 row of fonction",
     *      operationId="getFonctionById",
     *      description="Show one row of fonction",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="fonctionId",
     *          description="ID of fonction that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Fonction")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Fonction not found"),
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
        $fonctions = Fonction::find($id);
        //Test si le film exist
        if (empty($fonctions)) {
            return response()->json(
                ['error' => 'this fonction does not exist !'],
                404
            );
        }
        return $fonctions;
    }


    /**
     * @SWG\Put(
     *     path="/fonction/{fonctionId}",
     *     tags={"fonction"},
     *     operationId="updateFonction",
     *     summary="Update an existing fonction",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="fonctionId",
     *         in="path",
     *         description="Update the information of a fonction",
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
     *         name="salaire",
     *         in="formData",
     *         description="the field telephone you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="cadre",
     *         in="formData",
     *         description="the field cadre you want to update",
     *         required=false,
     *         type="boolean",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Fonction")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="Fonction not found",
     *     ),
     *     security={{"petstore_auth":{"write:fonction", "read:fonction"}}}
     * )
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
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

        $fonctions = Fonction::find($id);
        if (empty($fonctions)) {
            return response()->json(
                ['error' => 'fonction not found'],
                404
            );
        }

        $fonctions->fill(Input::all());
        $fonctions->save();

        return response()->json(
            ['message' => "fonction has been updated successfully"],
            200
        );

    }

    /**
     * @SWG\Delete(path="/fonction/{fonctionId}",
     *   tags={"fonction"},
     *   summary="Delete fonction order by ID",
     *   description="Delete a fonction with his ID",
     *   operationId="deleteFonction",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="fonctionId",
     *     in="path",
     *     description="ID of the fonction that needs to be deleted",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="fonction not found")
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
        $fonctions = Fonction::find($id);
        if (empty($fonctions)) {
            return response()->json(
                ['error' => 'there is no fonction for this id'],
                404
            );
        }
        $fonctions->delete();
        return response()->json(
            ['message' => "fonction resource deleted successfully"],
            200
        );
    }
}
