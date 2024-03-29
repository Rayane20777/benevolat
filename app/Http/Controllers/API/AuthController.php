<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register','logout','refresh']]);
    }

/**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authenticate user and generate JWT token",
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Login successful"),
     *     @OA\Response(response="401", description="Invalid credentials")
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();
        return response()->json([
            'user' => $user,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    /**
* @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     @OA\Parameter(
     *         name="name",
     *         in="query",
     *         description="User's name",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="email",
     *         in="query",
     *         description="User's email",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="User's Role either volenteer or organiser",
     *         required=true,
     *         @OA\Schema(type="enum")
     *     ),
     *     @OA\Response(response="201", description="User registered successfully"),
     *     @OA\Response(response="422", description="Validation errors")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

/**
 * Logout user and invalidate the current JWT token.
 *
 * @return \Illuminate\Http\JsonResponse
 *
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user and invalidate JWT token",
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged out",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Successfully logged out")
 *         )
 *     ),
 *     security={{"bearerAuth":{}}}
 * )
 */

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    
/**
 * Refresh the JWT token for the authenticated user.
 *
 * @return \Illuminate\Http\JsonResponse
 *
 * @OA\Post(
 *     path="/api/refresh",
 *     summary="Refresh JWT token for the authenticated user",
 *     @OA\Response(
 *         response=200,
 *         description="JWT token refreshed successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object", description="User details"),
 *             @OA\Property(
 *                 property="authorisation",
 *                 type="object",
 *                 description="Authorization information",
 *                 @OA\Property(property="token", type="string", description="New JWT token"),
 *                 @OA\Property(property="type", type="string", example="bearer", description="Type of token")
 *             )
 *         )
 *     ),
 *     security={{"bearerAuth":{}}}
 * )
 */

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}