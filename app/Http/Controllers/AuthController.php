<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Documentation",
 * )
 *
 * @OA\Tag(
 *     name="Authentication & Registration",
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   in="header",
 *   name="Authorization",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT",
 * ),
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="User registration",
     *     operationId="register",
     *     tags={"Authentication & Registration"},
     *     requestBody={"$ref": "#/components/requestBodies/RegisterRequest"},
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     *
     */
    public function registration(RegisterRequest $request): JsonResponse
    {
        $password = Hash::make($request->post('password'));
        User::create([
            'email' => $request->post('email'),
            'password' => $password
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/auth/login",
     *      operationId="login",
     *      tags={"Authentication & Registration"},
     *      summary="User log in",
     *      description="Returns user data with token",
     *      requestBody={"$ref": "#/components/requestBodies/LoginRequest"},
     *      @OA\Response(
     *          response=200,
     *          description="OK",
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Content",
     *      ),
     * )
     */

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->post('email'))->first();
        if (!$user || !Hash::check($request->post('password'), $user->password)) {
            throw new HttpResponseException(response()->json([
                'success'   => false,
                'message'   => 'Incorrect email or password',
            ], 422));
        }

        return response()->json([
            'success'   => true,
            'type'      => 'Bearer',
            'access_token'     => $user->createToken('api')->plainTextToken,
            'user'      => $user,
        ]);
    }

    /**
     * @OA\Get (
     *     path="/api/auth/me",
     *     summary="User info",
     *     description="Returns user data",
     *     operationId="me",
     *     tags={"Authentication & Registration"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *     )
     * )
     */
    public function me(): JsonResponse
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="User log out",
     *     description="Method revokes the current access token.",
     *     operationId="logout",
     *     tags={"Authentication & Registration"},
     *     security={
     *     {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *          response=200,
     *          description="OK",
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
