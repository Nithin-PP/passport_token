<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;

class UserController extends Controller
{
    public function index(){
        return "hello";
    }
    public function register(Request $request){
        $validation = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);
        if($validation->fails()){
            return response()->json($validation->errors(), 202);
        }
        $allData = $request->all();
        $allData['password'] = bcrypt($allData['password']);

        $user = User::create($allData);
        $res = [];
        $res['token'] = $user->createToken('api-application')->accessToken;
        $res['name'] = $user->name;
        return response()->json($res, 200);
    }
    public function login (Request $request){
        if(Auth::attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ])){
            
            $user = Auth::User();
            $res = [];
            $res['token'] = $user->createToken('api-application')->accessToken;
            $res['name'] = $user->name;
            return response()->json($res, 200);
        }else{
            return response()->json(['errors'=>'Unauthorized Access'], 203);
        }

    }
}
