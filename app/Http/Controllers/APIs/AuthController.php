<?php

namespace App\Http\Controllers\APIs;

use Exception;
use ArrayObject;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * register
     *
     * @group authentication
     */
    public function register(Request $request)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|unique:users,phone|min:10',
            'password' => 'required',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            /* register user */
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            /* create access token */
            $token = $user->createToken('accessToken')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Signup successfully.',
                'data' => $user,
                'assess_token' => $token
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * login
     *
     * @group authentication
     */
    public function login(Request $request)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            /* check credentials */
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                /* create access token */
                $user = Auth::user();
                $token = $user->createToken('accessToken')->accessToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully.',
                    'data' => $user,
                    'assess_token' => $token
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Credentials not match!'
                ], 400);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
