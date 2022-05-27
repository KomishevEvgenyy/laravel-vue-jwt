<?php

namespace App\Http\Controllers;


use App\Http\Resources\UserResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function list(Request $request)
    {
        $this->checkToken($request);

        $users = User::where('id', '!=', $this->user->id);
        if ($this->user->customer->role == Customer::ROLE_USER) {
            $users->where('is_active', User::STATUS_ACTIVE)->whereHas('customer', function (Builder $query) {
                $query->where('role', '=', Customer::ROLE_USER);
            });
        } else {
            $users->with('customer');
        }
        $data = UserResource::collection($users->get());

        return response()->json(compact('data'));
    }

    public function create(Request $request)
    {
        $current_user = $this->checkToken($request);
        if ($current_user->customer->role == Customer::ROLE_ADMIN) {
            $validator = Validator::make($request->all(), [
                'first_name' => 'string|between:2,255',
                'last_name' => 'string|between:2,255',
                'name' => 'required|string|between:2,255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|confirmed|min:8|max:255',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()]);
            }

            $user = User::create(array_merge($validator->validated(), [
                'password' => Hash::make($request->password)
            ]));

            Customer::create(array_merge($validator->validated(), [
                'user_id' => $user->id
            ]));

            return response()->json([
                'success' => true,
                'message' => 'User is created'
            ]);
        }

        return response()->json(['error' => true, 'message' => 'This user can\'t create']);

    }

    public function update(Request $request)
    {
        $current_user = $this->checkToken($request);
        if ($current_user->customer->role == Customer::ROLE_ADMIN) {
            $validator = Validator::make($request->only('first_name', 'last_name', 'email', 'active'), [
                'first_name' => 'string|between:2,255',
                'last_name' => 'string|between:2,255',
                'email' => 'string|email|max:255',
                'active' => 'required|boolean'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->messages()]);
            }
            $user = User::where('id', $request->id)->with('customer')->first();

            $user->is_active = $request->active;
            $user->customer->first_name = $request->first_name;
            $user->customer->last_name = $request->last_name;
            if ($user->email != $request->email) {
                $user->email = $request->email;
            }

            $user->customer->save();
            $user->save();

            $data = new UserResource($user);

            return response()->json([
                'success' => true,
                'message' => 'User is updated',
                'data' => $data
            ]);
        }

        return response()->json(['error' => true, 'message' => 'This user can\'t update']);

    }

    public function show(Request $request)
    {
        $this->checkToken($request);
        $validator = Validator::make($request->only('user_id'), [
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = new UserResource(User::where('is_active', 1)->where('id', $request->user_id)->with('customer')->first());


        return response()->json(compact('data'), Response::HTTP_OK);
    }

    protected function checkToken($request)
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            return JWTAuth::authenticate($request->token);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
