<?php

namespace App\Http\Controllers\APIs;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * get all user list with pagination.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get user list successfully',
                'data' => User::paginate(15)
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * get user profile by search.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function search(Request $request)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'search' => 'required',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $users = User::where(function ($query) use ($request) {
                $query->orWhere('name', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('email', 'LIKE', '%' . $request->search . '%');
                $query->orWhere('phone', 'LIKE', '%' . $request->search . '%');
            })->paginate(15);

            if ($users) {

                return response()->json([
                    'status' => true,
                    'message' => 'Get search user profile successfully',
                    'data' => $users
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * get user profile by id.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function show(string $id)
    {
        try {

            $user = User::where('id', $id)->first();

            if ($user) {

                return response()->json([
                    'status' => true,
                    'message' => 'Get user profile successfully',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * update user profile by id.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function update(Request $request, string $id)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|numeric|unique:users,phone,' . $id . '|min:10',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $user = User::where('id', $id)->first();

            if ($user) {

                $user->update(
                    $request->only('name', 'email', 'phone')
                );
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'User profile updated successfully',
                    'data' => $user
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * delete user profile by id.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function destroy(string $id)
    {
        try {

            $user = User::where('id', $id)->first();

            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'User profile deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * update user password by id.
     *
     * @group user
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function updatePassword(Request $request, string $id)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'password' => 'required'
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $user = User::where('id', $id)->first();

            if ($user) {

                $user->update([
                    'password' => Hash::make($request->password)
                ]);
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => 'User password updated successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found.',
                ], 200);
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
