<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\catat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CatatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function catat(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
        ]);

        $catat = new catat([
            'user_id' => auth()->id(),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
        ]);
        
        $catat->save();

        return response()->json(['message' => 'Money recording created'], 201);
    }

    
}
