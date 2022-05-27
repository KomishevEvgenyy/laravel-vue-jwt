<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Validator};
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JWTAuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|confirmed|min:8|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), Response::HTTP_BAD_REQUEST);
        }

        $user = User::create(array_merge($validator->validated(), [
            'password' => Hash::make($request->password)
        ]));

        Customer::create([
            'user_id' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully'
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $credentials = $request->only(['email', 'password']);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'error' => false,
                'message' => 'Login credentials are invalid.',
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ], Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        JWTAuth::invalidate($request->token);

        return response()->json([
            'success' => true,
            'message' => 'User has been logged out'
        ], Response::HTTP_OK);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return response()->json([
                'error' => false, 'message' => 'Token not provided'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWTAuth::refresh($token);

        return response()->json(compact('token'), Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = new UserResource(JWTAuth::authenticate($request->token));

        return response()->json(compact('data'), Response::HTTP_OK);
    }
}
