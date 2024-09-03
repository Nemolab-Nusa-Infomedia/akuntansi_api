<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\LogActivity;
use App\Models\PaymentSubscription;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

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

    public function createVendor(Request $request){

        $validate = Validator::make($request->all(), [
            'subscription_id' => 'required|string',
            'role_id' => 'required|integer',
            'name' => 'required|string',
            'email' => 'required|string|email:dns|unique:users',
            "phone" => "required",
            "password" => ['required', 'confirmed', Rules\Password::defaults()],
            'category_id' => 'required|integer',
            'name_company' => 'required|string',
            'location' => 'required|string',
        ]);

        try {
            if($validate->fails()){
                return response()->json([
                    'status' => 400,
                    'message' => $validate->errors()
                ], 400);
            } else {
                DB::beginTransaction();
                $data = $validate->validated();

                $subs = Subscription::firstWhere('id', $data['subscription_id']);

                Log::info('Subscription ID:', ['subs_id' => $subs->id]);

                // Create new user
                $data['password'] = Hash::make($data['password']);
                $user = User::create([
                    'name' => $data['name'],
                    'role_id' => $data['role_id'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'status_accont' => "active",
                    'password' => $data['password'],
                ]);

                // Create new company
                $company = Company::create([
                    'category_id' => $data['category_id'],
                    'subscription_id' => $data['subscription_id'],
                    'name' => $data['name_company'],
                    'location' => $data['location'],
                    'sub_from' => Carbon::now(),
                    'sub_to' => Carbon::now()->addYears($subs->duration),
                    'status' => 'active'
                ]);

                $create = PaymentSubscription::create([
                    'amount' => $subs->price,
                    'status' => 'success',
                    'user_id' => $user->id,
                    'company_id' => $company->id,
                    'subscription_id' => $subs->id,
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Vendor has been created',
                    'data' => $create
                ]);

            }
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
