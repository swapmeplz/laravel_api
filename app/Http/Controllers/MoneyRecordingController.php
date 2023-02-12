<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoneyRecording;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

class MoneyRecordingController extends Controller
{
    public function store(Request $request)
    {
        $validator = validator::make($request->all(),[
            'amount' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'gagal',
                'data' => $validator->errors()
            ]);
        }
        $moneyRecording = new MoneyRecording();
        $moneyRecording->user_id = $request->user()->id;
        $moneyRecording->amount = $request->input('amount');
        $moneyRecording->description = $request->input('description');
        $moneyRecording->save();

            return response()->json([
                'status' => true,
                'message' => 'sukses',
                'data' => $moneyRecording
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'numeric',
            'description' => 'string',
            ]);

            $moneyRecording = moneyRecording::find($id);
            if ($moneyRecording) {
                $moneyRecording-> user_id = $request->user()->id;
                $moneyRecording-> amount = $request->amount;
                $moneyRecording-> description = $request->description;
                $moneyRecording-> update();
    
                return response()->json([
                    'status' => true,
                    'message' => 'sukses',
                    'data'=>  $moneyRecording 
                ]);
            }
            else{
                return response()->json([
                    'status' => true,
                    'message' => 'gagal',
                    'data' =>  $validator->errors()
            ]);
        }
    }

    public function recap()
    {
        $recap = DB::table('money_recordings')
                ->select('users.name', 'users.email', DB::raw('SUM(money_recordings.amount) as total'))
                ->join('users', 'users.id', '=', 'money_recordings.user_id')
                ->groupBy('users.id')
                ->get();
        return response()->json($recap);
    }
}