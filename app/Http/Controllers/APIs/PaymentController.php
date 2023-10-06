<?php

namespace App\Http\Controllers\APIs;

use Exception;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    /**
     * initiate payment.
     *
     * @group payment
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function initiate(Request $request)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'invoice_id' => 'required',
            'txn_id' => 'required',
            'amount' => 'required',
            'status' => 'required|in:0,1',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $payment = Payment::create(
                $request->only('user_id', 'product_id', 'invoice_id', 'txn_id', 'amount', 'status')
            );

            return response()->json([
                'status' => true,
                'message' => 'Payment added successfully',
                'data' => $payment
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * get all success payment list with pagination.
     *
     * @group payment
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function success()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get success payment list successfully',
                'data' => Payment::with('user', 'product')->success()->paginate(15)
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * get all failed payment list with pagination.
     *
     * @group payment
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function failed()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get failed payment list successfully',
                'data' => Payment::with('user', 'product')->failed()->paginate(15)
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
