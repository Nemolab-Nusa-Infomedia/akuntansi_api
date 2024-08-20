<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogActivity;

class UserController extends Controller
{
    public function getActivities(Request $request)
    {
        try {
            $activities = LogActivity::where("user_id", auth("sanctum")->user()->id)->get();

            return response()->json([
                'status' => 200,
                'message' => "Success get data activities.",
                'data' => [
                    "activities" => $activities
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
