<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Models\CompanyCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auths')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get("/roles", [AuthController::class, "getRoles"]);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('companies')->group(function () {
        Route::get("/subscriptions", [CompanyController::class, "getSubscriptions"]);
        Route::get("/categories", [CompanyController::class, "getCategories"]);

        Route::post("/create-company", [CompanyController::class, "createCompany"]);

        Route::get("/user-companies", [CompanyController::class, "getCompanyByIdUser"]);
        Route::get("/company-users/{id}", [CompanyController::class, "getUsersByCompany"]);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::get("/activities", [UserController::class, "getActivities"]);

        Route::post('/create/vendor', [UserController::class, 'createVendor'])->middleware('isSuperAdmin');
    });
});
