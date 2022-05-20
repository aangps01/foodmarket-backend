<?php

namespace App\Http\Controllers\API;

use App\Models\Food;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FoodController extends Controller
{
    public function getFood(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::failedValidation($validator->errors());
        }

        $food = Food::find($request->id);

        if ($food) {
            return ResponseFormatter::success($food, 'Successfully get data');
        }

        return ResponseFormatter::error([
            'message' => 'Food data with ID: ' . $request->id . " not found"
        ], 'Data not found', 404);
    }

    public function getFilteredFoods(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'integer|nullable',
            'name' => 'string',
            'type' => 'string',
            'price_from' => 'integer',
            'price_to' => 'integer',
            'rate_from' => 'integer',
            'rate_to' => 'integer'
        ]);

        $limit = $request->limit ?? 5;
        $name = $request->name;
        $type = $request->type;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $rate_from = $request->rate_from;
        $rate_to = $request->rate_to;

        $food = Food::query();

        if ($name) {
            $food->where('name', 'like', '%' . $name . '%');
        }

        if ($type) {
            $food->where('type', $type);
        }

        if ($price_from) {
            $food->where('price', '>=', $price_from);
        }

        if ($price_to) {
            $food->where('price', '<=', $price_to);
        }

        if ($rate_from) {
            $food->where('rate', '>=', $rate_from);
        }

        if ($rate_to) {
            $food->where('rate', '<=', $rate_to);
        }

        $foods = $food->paginate($limit);
        return ResponseFormatter::success(
            $foods,
            'Successfully get data'
        );
    }
}
