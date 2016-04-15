<?php

namespace App\Http\Controllers;

use App\HistoriqueMembre;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;

class HistoriqueMembreController extends Controller
{
    /**
     * @SWG\Get(path="/historiqueMembre",
     *   tags={"historiqueMembre"},
     *   operationId="getHistoriqueMembre",
     *   summary="Display the historique of members.",
     *   description="This can only be done by the logged in user.",
     *   produces={"application/json"},
     *   @SWG\Response(
     *     response=200,
     *     description="successful operation",
     *     @SWG\Schema(
     *      type="array",
     *      @SWG\Items(ref="#/definitions/HistoriqueMembre")
     *     ),
     *   ),
     * )
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $historiqueMembres = HistoriqueMembre::all();
        //->take(5)
        return $historiqueMembres;
    }


    /**
     * @SWG\Post(path="/historiqueMembre",
     *     tags={"historiqueMembre"},
     *     summary="add a historique",
     *     operationId="addHistoriqueMembre",
     *     description="This is to insert a row of historique member",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_membre",
     *         in="formData",
     *         description="Enter the member id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_seance",
     *         in="formData",
     *         description="Enter the seance id",
     *         required=true,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="date",
     *         in="formData",
     *         description="Enter the date",
     *         required=false,
     *         type="string",
     *         format="date-time",
     *     ),
     *      @SWG\Response(
     *          response=201,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/HistoriqueMembre")
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
            'id_membre' => 'required|exists:membres,id_membre',
            'id_seance' => 'required|exists:seances,id',
            'date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $historiqueMembre = HistoriqueMembre::create(Input::all());
        $historiqueMembre->save();
        return response()->json(
            ['Historique membre' => $historiqueMembre],
            201
        );
    }

    /**
     * @SWG\Get(path="/historiqueMembre/{historiqueMembreId}",
     *      tags={"historiqueMembre"},
     *      summary="show the historique for the ID in params",
     *      operationId="getHistoriqueMembreById",
     *      description="show the historique for the ID in params",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="historiqueMembreId",
     *          description="ID of historique membre that needs to be fetched",
     *          required=true,
     *          in="path",
     *          type="integer"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/HistoriqueMembre")
     *          ),
     *      ),
     *      @SWG\Response(response=400, description="Invalid ID supplied"),
     *      @SWG\Response(response=404, description="Historique membre not found"),
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
        $historiqueMembre = HistoriqueMembre::find($id);

        if (empty($historiqueMembre)) {
            return response()->json(
                ['error' => 'this historique does not exist !'],
                404
            );
        }
        return $historiqueMembre;
    }


    /**
     * @SWG\Put(
     *     path="/historiqueMembre/{historiqueMembreId}",
     *     tags={"historiqueMembre"},
     *     operationId="updateHistoriqueMembre",
     *     summary="Update an existing historique",
     *     description="To update an existing historique with ID provided",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="historiqueMembreId",
     *         in="path",
     *         description="Enter the id of the historique to update",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="id_membre",
     *         in="formData",
     *         description="Enter the id membre of the historique to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_seance",
     *         in="formData",
     *         description="Enter the id seance of the historique to update",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="date",
     *         in="formData",
     *         description="Enter the date of the historique to update",
     *         required=false,
     *         type="string",
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="Invalid ID supplied",
     *         @SWG\Schema(
     *              type="array",
     *              @SWG\Items(ref="#/definitions/HistoriqueMembre")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="HistoriqueMembre not found",
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
        //Validation des parametres a sauvegarder;
        $validator = Validator::make($request->all(), [
            'id_membre' => 'required|exists:membres,id_membre',
            'id_seance' => 'required|exists:seances,id',
            'date' => 'required|date_format:Y-m-d H:i:s',
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

        $historiqueMembre = HistoriqueMembre::find($id);
        if (empty($historiqueMembre)) {
            return response()->json(
                ['error' => 'historiqueMembre not found'],
                404
            );
        }

        $historiqueMembre->fill(Input::all());
        $historiqueMembre->save();

        return response()->json(
            ['message' => "historiqueMembre has been updated successfully"],
            200
        );

    }

    /**
     * @SWG\Delete(path="/historiqueMembre/{historiqueMembreId}",
     *   tags={"historiqueMembre"},
     *   summary="Delete a historiqueMembre",
     *   description="Delete a historiqueMembre with its ID",
     *   operationId="deleteHistoriqueMembre",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="historiqueMembreId",
     *     in="path",
     *     description="ID of the historiqueMembre that needs to be deleted",
     *     required=true,
     *     type="integer"
     *   ),
     *   @SWG\Response(response=400, description="Invalid ID supplied"),
     *   @SWG\Response(response=404, description="Order not found"),
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
        $historiqueMembre = HistoriqueMembre::find($id);
        if (empty($historiqueMembre)) {
            return response()->json(
                ['error' => 'there is no historiqueMembre for this id'],
                404
            );
        }
        $historiqueMembre->delete();
        return response()->json(
            ['message' => "historiqueMembre resource deleted successfully"],
            200
        );
    }

    /**
     * @SWG\Get(
     *     path="/historiqueMembre/getNumberEntry",
     *     summary="Get number entry",
     *     tags={"historiqueMembre"},
     *     description="return number entry by filter",
     *     operationId="getNumberEntry",
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id_film",
     *         in="query",
     *         description="Id film",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_distributeur",
     *         in="query",
     *         description="Id distributeur",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="id_genre",
     *         in="query",
     *         description="Id genre",
     *         required=false,
     *         type="integer",
     *     ),
     *     @SWG\Parameter(
     *         name="date_seance",
     *         in="query",
     *         description="Enter seance date",
     *         required=false,
                type="string",
     *         format="date-time",
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
     *         description="seance not found",
     *     ),
     * )
     */
    public function getNumberEntry(Request $request)
    {
        \DB::enableQueryLog();

        $filters = ['id_film', 'id_distributeur', 'id_genre',
            'date_seance'];

        //Validation des parametres a sauvegarder
        $validator = Validator::make($request->all(), [
            'id_film' => 'exists:films,id_film',
            'id_distributeur' => 'exists:distributeurs,id_distributeur',
            'id_genre' => 'exists:genres,id_genre',
            'date_seance' => 'date_format:Y-m-d H:i:s',
        ]);

        if ($validator->fails()) {
            return response()->json(
                ['errors' => $validator->errors()->all()],
                422
            );
        }

        $query = HistoriqueMembre::whereNotNull('historique_membre.id_historique');
        var_dump(\DB::getQueryLog());

        $query->join('seances', 'historique_membre.id_seance', '=', 'seances.id');

        if (null !== $request->id_film) {
            $query->join('films', function($join) use ($request) {
                $join->on('seances.id_film', '=', 'films.id_film')
                    ->where('films.id_film', '=', $request->id_film);
            });
        } elseif (null !== $request->id_distributeur) {
            $query->join('films','seances.id_film', '=', 'films.id_film');
            $query->join('distributeurs', function($join) use ($request) {
                $join->on('films.id_distributeur', '=', 'distributeurs.id_distributeur')
                    ->where('distributeurs.id_distributeur', '=', $request->id_distributeur);
            });
        } elseif (null !== $request->id_genre) {
            $query->join('films', 'seances.id_film', '=', 'films.id_film');
            $query->join('distributeurs', function ($join) use ($request) {
                $join->on('films.id_distributeur', '=', 'distributeurs.id_distributeur')
                    ->where('distributeurs.id_distributeur', '=', $request->id_distributeur);
            });
        }

        var_dump(\DB::getQueryLog());

        $nbEntrees = $query->count();

        if (empty($nbEntrees)) {
            return response()->json(
                ['error' => 'there is no entrees'],
                404
            );
        }

        return response()->json(
            ['entries' => $nbEntrees],
            200
        );
    }
}
