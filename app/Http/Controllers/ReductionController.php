<?php

namespace App\Http\Controllers;

use App\Reduction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class ReductionController extends Controller
{
    /**
     * @SWG\Get(path="/reduction",
     *   tags={"reduction"},
     *   operationId="getReduction",
     *   summary="Display a list of reductions.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Reduction")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reductions = Reduction::all()->take(5);
        return $reductions;
    }

    /**
     * @SWG\POST(path="/reduction",
     *     tags={"reduction"},
     *     summary="add a new reduction",
     *     operationId="addReduction",
     *     description="This is to insert a reduction in the databse",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Enter the name of the reduction",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut",
     *         in="formData",
     *         description="Enter the starting date of the reduction",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin",
     *         in="formData",
     *         description="Enter the ending date of the reduction,
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pourcentage_reduction",
     *         in="formData",
     *         description="Enter the percentage of the reduction",
     *         required=false,
     *         type="integer",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Reduction")
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
            'nom' => 'required|unique:reductions',
            'date_debut' => 'required|date_format:Y-m-d H:i:s|before:date_fin',
            'date_fin' => 'required|date_format:Y-m-d H:i:s|after:date_debut',
            'pourcentage_reduction' => 'required|integer|between:0,100',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $reductions = new Reduction();
        $reductions->nom = $request->nom;
        $reductions->date_debut = $request->date_debut;
        $reductions->date_fin = $request->date_fin;
        $reductions->pourcentage_reduction = $request->pourcentage_reduction;
        $reductions->save();
        return response()->json(
            ['Reduction' => $reductions],
            201
        );
    }

    /**
     * @SWG\Get(path="/reduction/{reductionId}",
     *      tags={"reduction"},
     *      summary="show a reduction",
     *      operationId="getReductionById",
     *      description="Show a reduction with ID provided",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="reductionId",
     *          description="ID of reduction that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Reduction")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Reduction not found"),
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
        $reductions = Reduction::find($id);
        //Test si le reduction exist
        if (empty($reductions)) {
            return response()->json(
                ['error' => 'this reduction does not exist bitch'],
                404
            );
        }
        return $reductions;
    }

    /**
     * @SWG\Put(
     *     path="/reduction/{reductionId}",
     *     tags={"reduction"},
     *     operationId="updateReduction",
     *     summary="Update an existing reduction",
     *     description="To update an existing reduction with ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="nom",
     *         in="formData",
     *         description="Enter the new name of the reduction",
     *         required=true,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_debut",
     *         in="formData",
     *         description="Enter the new starting date of the reduction",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="date_fin",
     *         in="formData",
     *         description="Enter the new ending date of the reduction",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="pourcentage_reduction",
     *         in="formData",
     *         description="Enter the new percentage of the reduction",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Reduction")
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
            'nom' => 'required|unique:reductions',
            'date_debut' => 'required|date_format:Y-m-d H:i:s|before:date_fin',
            'date_fin' => 'required|date_format:Y-m-d H:i:s|after:date_debut',
            'pourcentage_reduction' => 'required|integer|between:0,100',
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

        $reductions = Reduction::find($id);
        if (empty($reductions)) {
            return response()->json(
                ['error' => 'Reduction not found'],
                404
            );
        }

        $reductions->nom = $request->nom;
        $reductions->date_debut = $request->date_debut;
        $reductions->date_fin = $request->date_fin;
        $reductions->pourcentage_reduction = $request->pourcentage_reduction;
        $reductions->save();

        return response()->json(
            ['Reduction' => $reductions],
            201
        );
    }

    /**
     * @SWG\Delete(path="/reduction/{reductionId}",
     *   tags={"reduction"},
     *   summary="Delete a reduction",
     *   description="To delete a reduction with ID provided",
     *   operationId="deleteReduction",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="reductionId",
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
        $reductions = Reduction::find($id);
        if (empty($reductions)) {
            return response()->json(
                ['error' => 'there is no reduction for this id'],
                404
            );
        }
        $reductions->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
