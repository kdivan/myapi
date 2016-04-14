<?php

namespace App\Http\Controllers;

use App\Seance;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class SeanceController extends Controller
{
    /**
     * @SWG\Get(path="/seance",
     *   tags={"seance"},
     *   operationId="getSeance",
     *   summary="Display a list of seances.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/Seance")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seances = Seance::all();
        return $seances;
    }

    /**
     * @SWG\Post(path="/seance",
     *     tags={"seance"},
     *     summary="Add a new seance.",
     *     operationId="addSeance",
     *     description="This is to insert a film",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_ouvreur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_technicien",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_menage",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=true,
     *         type="string",
     *         format="date-time",
     *     ),
     *     @SWG\Parameter(
     *         name="fin_seance",
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
     *              @SWG\Items(ref="#/definitions/Seance")
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
            'id_film' => 'required|exists:film,id_film',
            'id_salle' => 'required|exists:salle,id_salle',
            'id_personne_ouvreur' => 'required|exists:personne,id_personne',
            'id_personne_technicien' => 'required|exists:personne,id_personne',
            'id_personne_menage' => 'required|exists:personne,id_personne',
            'debut_seance' => 'required|date_format:Y-m-d H:i:s|after:now|before:fin_seance',
            'fin_seance' => 'required|date_format:Y-m-d H:i:s|after:debut_seance',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }
        $seance = Seance::create(Input::all());
        $seance->save();
        return response()->json(
            ['Seance' => $seance],
            Response::HTTP_CREATED
        );
    }

    /**
     * @SWG\Get(path="/seance/{seanceId}",
     *      tags={"seance"},
     *      summary="Show 1 row",
     *      operationId="getSeanceById",
     *      description="Show one row",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="seanceId",
     *          description="ID of seance that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Seance")
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
        $seance = Seance::find($id);
        //Test si le film exist
        if (empty($seance)) {
            return response()->json(
                ['error' => 'this seance does not exist'],
                404
            );
        }
        return $seance;
    }

    /**
     * @SWG\Put(
     *     path="/seance/{seanceId}",
     *     tags={"seance"},
     *     operationId="update seance",
     *     summary="Update an existing seance",
     *     description="",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="seanceId",
     *         in="path",
     *         description="Seance object",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_salle",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_ouvreur",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_technicien",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="id_personne_menage",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="debut_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Parameter(
     *         name="fin_seance",
     *         in="formData",
     *         description="the fields you want to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/Film")
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
        //Validation des parametres a sauvegarder
        $validator = Validator::make($request->all(), [
            'id_film' => 'exists:film,id_film',
            'id_salle' => 'exists:salle,id_salle',
            'id_personne_ouvreur' => 'exists:personne,id_personne',
            'id_personne_technicien' => 'exists:personne,id_personne',
            'id_personne_menage' => 'exists:personne,id_personne',
            'debut_seance' => 'date',
            'fin_seance' => 'date',
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

        $seance = Seance::find($id);
        if (empty($seance)) {
            return response()->json(
                ['error' => 'Film not found'],
                404
            );
        }

        $seance->fill(Seance::all());
        $seance->save();

        return response()->json(
            ['Fields have been correctly update'],
            Response::HTTP_OK
        );

    }


    /**
     * @SWG\Delete(path="/seance/{seanceId}",
     *   tags={"seance"},
     *   summary="Delete seance by id",
     *   operationId="deleteSeance",
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
        $seance = Seance::find($id);
        if (empty($seance)) {
            return response()->json(
                ['error' => 'there is no film for this id'],
                404
            );
        }
        $seance->delete();
        return response()->json(
            ['message' => "resource deleted successfully"],
            200
        );
    }
}
