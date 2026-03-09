<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message" => "API BeTalent Ativa"]);
});

Route::middleware('auth:sanctum')->group(function () {

});