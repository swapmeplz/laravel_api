<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'data kosong',
                'data' => $validator->errors()
            ]);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = user::create($input);

        $success['token'] = $user->createToken('auth_token')->plainTextToken;
        $success['name'] = $user->name;
        
        return response()->json([
            'status' => true,
            'message' => 'get sukses',
            'data' => $success
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $auth = Auth::user();
            $success['token'] = $auth->createToken('auth_token')->plainTextToken;
            $success['email'] = $auth->email;
            
            return response()->json([
                'status' => true,
                'message' => 'login berhasil',
                'data' => $success
            ]); 
        }else{
            return response()->json([
                'status' => false,
                'massage' => 'login gagal',
                'data' => null
            ]);
        }
    }

    public function show(Request $request)
    {
        return $request->user();
    }
    
    public function update(Request $request, $id)
    {
       $request->validate([
        'name' => 'string',
        'email' => 'email|unique:users,email,'.$id,
        'password' => 'string|min:6',
        ]);

        $user = User::find($id);
        if ($user) {
            $user-> name = $request->name;
            $user-> email = $request->email;
            $user-> password = $request->password;
            $user-> update();

            return response()->json([
                'status' => true,
                'message' => 'sukses',
                'data'=> $user
            ]);
        }
        else{
            return response()->json([
                'status' => false,
                'massage' => 'fail'
            ]);
        }
    }
}
