<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::orderBy('created_at','DESC')->paginate(8);
        return view('admin.users.user',compact('users'));
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('admin.users.edit',compact('user'));
    }

    public function update(Request $request, $id){
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:5|max:20',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id), // Ignore unique check for this user's current email
            ],
        ]);
    
        if($validator->passes()){
            $user = User::find($id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->phone = $request->phone;
            $user->save();
    
            session()->flash('success','User Information Updated Successfully. ');
            return response()->json([
                'status' => true,
                'errors' =>[],
            ]);
        }else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function destroy(Request $request){
        $id = $request->id;
        $user = User::find($id);
        if($user == null){
            session()->flash('error', 'User Not Found');
            return response()->json([
                'status' => false,
                'errors' =>[],
            ]);
        }

        $user->delete();
        session()->flash('success', 'User Deleted Successfully');
        return response()->json([
            'status' => true,
           
        ]);
    }
}
