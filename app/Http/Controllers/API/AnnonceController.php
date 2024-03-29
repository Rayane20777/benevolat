<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Annonce;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

use Validator;

class AnnonceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Retrieve all annonces.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Get(
     *     path="/api/annonce",
     *     summary="Retrieve all annonces",
     *     @OA\Response(response="200", description="Annonces retrieved successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function index(){

            $annonces = QueryBuilder::for(Annonce::class)
            ->allowedFilters('type','localisation')
            ->get();

            return response()->json($annonces);
    }

    /**
     * Store a new annonce.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/annonce/store",
     *     summary="Store a new annonce",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre", "description", "date", "localisation", "type", "competence"},
     *             @OA\Property(property="titre", type="string", description="Title of the annonce"),
     *             @OA\Property(property="description", type="string", description="Description of the annonce"),
     *             @OA\Property(property="date", type="string", format="date", description="Date of the annonce"),
     *             @OA\Property(property="localisation", type="string", description="Location of the annonce"),
     *             @OA\Property(property="type", type="string", description="Type of the annonce"),
     *             @OA\Property(property="competence", type="string", description="Competence required for the annonce")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Annonce stored successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function store(Request $request){
        $user_id = Auth::user()->id;    

        $request->validate([
            'titre'=>'required',
            'description'=>'required',
            'date'=>'required',
            'localisation'=>'required',
            'type'=>'required',
            'competence'=>'required',
        ]);


        $annonce=Annonce::create([
            'titre'=>$request->titre,
            'description'=>$request->description,
            'date'=>$request->date,
            'localisation'=>$request->localisation,
            'type'=>$request->type,
            'competence'=>$request->competence,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'message'=> 'success',
            'annonce'=> $annonce,
        ]);
    }

    
    /**
     * Delete an annonce.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/api/annonce/{id}/delete",
     *     summary="Delete an annonce",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the annonce",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Annonce deleted successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function delete($id){
        $annonce=Annonce::find($id);
        $annonce->delete();

        return response()->json([
            'message'=> 'deleted succesfuly',
            ]);
    }

    /**
     * Update an annonce.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Annonce  $annonce
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Put(
     *     path="/api/annonce/{annonce}/update",
     *     summary="Update an annonce",
     *     @OA\Parameter(
     *         name="annonce",
     *         in="path",
     *         description="ID of the annonce",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre", "description", "date", "localisation", "type", "competence"},
     *             @OA\Property(property="titre", type="string", description="Title of the annonce"),
     *             @OA\Property(property="description", type="string", description="Description of the annonce"),
     *             @OA\Property(property="date", type="string", format="date", description="Date of the annonce"),
     *             @OA\Property(property="localisation", type="string", description="Location of the annonce"),
     *             @OA\Property(property="type", type="string", description="Type of the annonce"),
     *             @OA\Property(property="competence", type="string", description="Competence required for the annonce")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Annonce updated successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function update(Request $request, Annonce $annonce){
        $input= $request->all();

        $validator = Validator::make($input, [
            'titre'=>'required',
            'description'=>'required',
            'date'=>'required',
            'localisation'=>'required',
            'type'=>'required',
            'competence'=>'required',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());       
            }

            unset($input['user_id']);
   

            $annonce->update($input);
            

        return response()->json([
            "success" => true,
            "message" => "Annonce updated successfully.",
            "data" => $annonce
        ]);
        
    }
}
