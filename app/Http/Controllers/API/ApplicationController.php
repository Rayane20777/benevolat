<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

     /**
     * Store a new application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/application",
     *     summary="Store a new application",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"annonce_id"},
     *             @OA\Property(property="annonce_id", type="integer", description="ID of the announcement")
     *         )
     *     ),
     *     @OA\Response(response="200", description="Application stored successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function store(Request $request){
        $user_id = Auth::user()->id;    

        $request->validate([
            'annonce_id'=> 'required',
        ]);


        $application=Application::create([
            'annonce_id'=>$request->annonce_id,
            'user_id' => $user_id,
        ]);

        return response()->json([
            'message'=> 'success',
            'annonce'=> $application,
        ]);
    }

    /**
     * Refuse an application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/application/{id}/refuse",
     *     summary="Refuse an application",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the application",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Application refused successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function refuse(Request $request, $id)
    {
        $application = Application::findOrFail($id);

  
        $application->status = 'refused';
        $application->save();

        return response()->json([
            'message' => 'Application refused successfully',
            'application' => $application,
        ]);

        

        
    }

    /**
     * Confirm an application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Post(
     *     path="/api/application/{id}/confirm",
     *     summary="Confirm an application",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the application",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Application confirmed successfully"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function confirm(Request $request, $id)
    {
        $application = Application::findOrFail($id);

  
        $application->status = 'confirmed';
        $application->save();

        return response()->json([
            'message' => 'Application refused successfully',
            'application' => $application,
        ]);


}
}