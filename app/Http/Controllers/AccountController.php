<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class AccountController extends Controller
{
    //user registration page
 public function registration(){
    return view('frontend.account.registration');
 }

//  public function saveRegistration(Request $request){
//         $validator = Validator::make($request->all(),[
//             'name' => 'required',
//             'email' => 'required|email',
//             'password' => 'required|min:5|same:confirm_password',
//             'confirm_password' => 'required'
//         ]);

//         if($validator->passes()){
//             $user = new User();
//             $user->name = $request->name;
//             $user->email = $request->email;
//             $user->password = Hash::make($request->password);
     
//             $user->save();

//             session()->flash('success', 'You have registerd successfully');
//             return response()->json([
//                 'status' => true,
//                 'errors' => [],
//             ]);
//         }
//         else{
//             return response()->json([
//                 'status' => false,
//                 'errors' => $validator->errors(),
//             ]);
//         }
//  }

//  public function login(){
// return view('frontend.account.login');
//  }

 public function saveRegistration(Request $request){
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:5|same:confirm_password',
        'confirm_password' => 'required'
    ]);

    if($validator->passes()){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        session()->flash('success', 'You have registered successfully');
        return response()->json([
            'status' => true,
            'errors' => [],
        ]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }
}

// User login page
public function login(){
    return view('frontend.account.login');
}

public function authenticate(Request $request){
    $validator = Validator::make($request->all(),[
        'email' => 'required',
        'password' => 'required'
    ]);

    if($validator->passes()){

    }
    else{
        
    }
}

}
