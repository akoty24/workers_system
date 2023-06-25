<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientAuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:client', ['except' => ['login', 'register']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if (! $token = auth()->guard('client')->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->createNewToken($token);
    }
    /**
     * Register a Client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:clients',
            'password' => 'required|string|min:6',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $client = Client::create(array_merge(
            $validator->validated(),
            [   'password' => bcrypt($request->password),
                'photo' => $request->file('photo')->store('clients'),
            ]
        ));
        return response()->json([
            'message' => 'Client successfully registered',
            'user' => $client
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->guard('client')->logout();
        return response()->json(['message' => 'Client successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated Client.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->guard('client')->user());
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->guard('client')->user()
        ]);
    }
}
