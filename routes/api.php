<?php

//Libs
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Routes
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return response()->json(["message" => "API BeTalent Ativa"]);
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    
});