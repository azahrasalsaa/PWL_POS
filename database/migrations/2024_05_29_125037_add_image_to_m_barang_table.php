<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\UserModel;
use App\Http\Controllers\Controller;
USE Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PenjualanController extends Controller {
    public function __invoke(Request $request) {
        //set validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'pembeli' => 'required',
            'penjualan_tanggal' => 'required',
            'image' => 'required',
        ]);
        
        //if validations fails
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        // get the imsage file
        $image = $request->file('image');
        
        //create user
        $user = PenjualanModel::create([
            'user_id' => $request->user_id,
            'pembeli' => $request->pembeli,
            'penjualan_tanggal'=> $request->penjualan_tanggal,
            'image' => $image->hashName(),
        ]);

        //return response JSON user is created
        if($user){
            return response()->json([
                'success' => true,
                'user' => $user,
            ], 201);
        }

        //return JSON process insert failed
        return response()->json([
            'success' => false,
        ], 409);
    }
}