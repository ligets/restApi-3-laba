<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = validator()->make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|unique:users|string|digits:11',
            'document_number' => 'required|unique:users|string|digits:10',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->getMessageBag()
                ]
            ]);
        }
        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'document_number' => $request->document_number,
            'password' => bcrypt($request->password)
        ]);
        return response()->json('', 204);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $validator = validator()->make(request()->all(), [
            'phone' =>  'required|string|digits:11',
            'password' => 'required|string'
        ]);
        if($validator->fails()) {
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'Validation error',
                    'errors' => $validator->getMessageBag()
                ]
            ]);
        }
        $credentials = request(['phone', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => [
                    'code' => 401,
                    'message' => 'Unauthorized',
                    'errors' => [
                        'phone' => [
                            'phone or password incorrect'
                        ]
                    ]
                ]
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        return response()->json([
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone' => $user->phone,
            'document_number' => $user->document_number
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'data' => [
                'token' => $token,
//              'token_type' => 'bearer',
//              'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }
}
