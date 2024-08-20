<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\CompanyCategory;
use App\Models\Company;
use App\Models\LogActivity;
use App\Models\UserCompany;
use App\Models\PaymentSubscription;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function getSubscriptions(Request $request)
    {
        try {
            $subscriptions = Subscription::get();

            return response()->json([
                'status' => 200,
                'message' => "Success get data subscriptions.",
                'data' => [
                    "subscriptions" => $subscriptions
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ], 500);
        }
    }

    public function getCategories(Request $request)
    {
        try {
            $categories = CompanyCategory::get();

            return response()->json([
                'status' => 200,
                'message' => "Success get data categories.",
                'data' => [
                    "categories" => $categories
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th,
            ], 500);
        }
    }

    public function createCompany(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'category_id' => 'required',
            'subscription_id' => 'required',
            'name' => 'required',
            'location' => 'required'
        ]);

        try {
            if ($validate->fails()) {
                return response()->json([
                    'status' => 400,
                    'message' => $validate->errors()
                ], 400);
            }

            DB::beginTransaction();

            $subscription = Subscription::find($request->subscription_id);
            $date_now = Carbon::now();

            $company = Company::create([
                "id" => Str::uuid(),
                "category_id" => $request->category_id,
                "subscription_id" => $request->subscription_id,
                "name" => $request->name,
                "location" => $request->location,
                "sub_from" => $date_now,
                "sub_to" => $date_now->addYears($subscription->duration),
                "status" => "disabled"
            ]);

            UserCompany::create([
                "id" => Str::uuid(),
                "role" => "owner",
                "user_id" => auth("sanctum")->user()->id,
                "company_id" => $company->id
            ]);

            PaymentSubscription::create([
                "id" => Str::uuid(),
                "amount" => $subscription->price,
                "status" => "pending",
                "subscription_id" => $subscription->id,
                "company_id" => $company->id,
                "user_id" => auth("sanctum")->user()->id,
            ]);

            LogActivity::create([
                "id" => Str::uuid(),
                "title" => "Notifikasi Aktifitas Perusahaan",
                "body" => "Kamu baru saja membuat perusahaan baru dengan id $company->id.",
                "user_id" => auth("sanctum")->user()->id
            ]);

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => "Success create company",
                'data' => [
                    "company" => $company,
                    "url_payment" => ""
                ]
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getCompanyByIdUser(Request $request)
    {
        try {
            $companies = auth("sanctum")->user()->companies;

            return response()->json([
                'status' => 200,
                'message' => "Success get data companies",
                'data' => [
                    "companies" => $companies
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function getUsersByCompany(Request $request, $id)
    {
        try {
            $company = Company::find($id);

            if (!$company) {
                return response()->json([
                    'status' => 404,
                    'message' => "Company not found",
                ], 404);
            }

            $users = $company->users;

            return response()->json([
                'status' => 200,
                'message' => "Success get data users",
                'data' => [
                    "users" => $users
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
