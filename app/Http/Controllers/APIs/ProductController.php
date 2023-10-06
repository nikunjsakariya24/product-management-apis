<?php

namespace App\Http\Controllers\APIs;

use Exception;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * get all product list with pagination.
     *
     * @group product
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function index()
    {
        try {
            return response()->json([
                'status' => true,
                'message' => 'Get product list successfully',
                'data' => Product::paginate(15)
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * add new product.
     *
     * @group product
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function store(Request $request)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'original_amount' => 'required',
            'discounted_amount' => 'required',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $product = Product::create(
                $request->only('name', 'original_amount', 'discounted_amount')
            );

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully',
                'data' => $product
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * get product by id.
     *
     * @group product
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function show(string $id)
    {
        try {

            $product = Product::where('id', $id)->first();

            if ($product) {

                return response()->json([
                    'status' => true,
                    'message' => 'Get product successfully',
                    'data' => $product
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
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
     * update product by id.
     *
     * @group product
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function update(Request $request, string $id)
    {
        /* request rules */
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'original_amount' => 'required',
            'discounted_amount' => 'required',
        ]);

        /* validator response */
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 400);
        }

        try {

            $product = Product::where('id', $id)->first();

            if ($product) {

                $product->update(
                    $request->only('name', 'original_amount', 'discounted_amount')
                );
                $product->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Product updated successfully',
                    'data' => $product
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
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
     * delete product by id.
     *
     * @group product
     * @authenticated
     * @header Authorization Bearer {token}
     */
    public function destroy(string $id)
    {
        try {

            $product = Product::where('id', $id)->first();

            if ($product) {
                $product->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Product deleted successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
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
     * get product by search.
     *
     * @group product
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

            $products = product::where('name', 'LIKE', '%' . $request->search . '%')->paginate(15);

            if ($products) {

                return response()->json([
                    'status' => true,
                    'message' => 'Get search product successfully',
                    'data' => $products
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'product not found.',
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
