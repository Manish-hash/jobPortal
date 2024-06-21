<?php

namespace App\Http\Controllers;
use App\Models\Job;

use App\Models\User;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if($validator->passes()){
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){

            return redirect()->route('account.profile');
        } else{
            return redirect()->route('account.login')->with('error', 'Either Email/Password is incorrect');
        }
    }
    else{
        return redirect()->route('account.login')
        ->withErrors($validator)
        ->withInput($request->only('email'));
    }

    }

    public function profile(){

        $id = Auth::user()->id;
       $user= User::find($id);

       return view('frontend.account.profile',compact('user'));
}


public function updatePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'old_password' => 'required',
        'new_password' => 'required|min:6',
        'confirm_password' => 'required|same:new_password',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ]);
    }

    

    if (Hash::check($request->old_password, Auth::user()->password) == false) {
        session()->flash('error','Your Old Password is Incorrect');
        return response()->json([
            'status' => false,
            'errors' => ['old_password' => 'The old password is incorrect.'],
        ]);
    }

    // Update the password
    $user = User::find(Auth::user()->id);
    $user->password = Hash::make($request->new_password);
    $user->save();

    return response()->json([
        'status' => true,
        'success' => 'Password updated successfully.',
    ]);
}

public function updateUserProfile(Request $request){
    $id = Auth::user()->id;
    $validator = Validator::make($request->all(),[
        'name' => 'required|min:5|max:20',
        'email' => 'required|email|unique:users,email,'.$id.',id'
    ]);

    if($validator->passes()){
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->designation = $request->designation;
        $user->phone = $request->phone;
        $user->save();

        session()->flash('success','Profile Updated Successfully. ');
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

public function updateProfilePic(Request $request){
    $id = Auth::user()->id;
    $validator = Validator::make($request->all(), [
        'image' => 'required|image'
    ]);

    if ($validator->passes()) {
        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $imageName = $id . '-' . time() . '.' . $ext;
        $image->move(public_path('/profile_pic/'), $imageName);

        User::where('id', $id)->update(['image' => $imageName]);

        session()->flash('success', 'Profile Picture Updated Successfully');
        return response()->json(['status' => true]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }
}

public function logout(){
    Auth::logout();
    return redirect()->route('account.login');
}


public function createJob(){
    $job_types= JobType::orderBy('name', 'ASC')->where('status',1)->get();
    $categories= Category::orderBy('name', 'ASC')->where('status',1)->get();
    return view('frontend.account.job.create',compact('categories','job_types'));
}

public function saveJob(Request $request){
    $rules = [
        'title' => 'required|min:5|max:200',
        'category' => 'required',
        'jobType' => 'required',
        'vacancy' => 'required|integer',
        'location' => 'required|max:50',
        'description' => 'required',
        'company_name' => 'required|min:3|max:40',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->passes()) {
        $job = new Job();
        $job->title = $request->title;
        $job->category_id = $request->category;
        $job->job_type_id = $request->jobType;
        $job->user_id = Auth::user()->id;
        $job->vacancy = $request->vacancy;
        $job->salary = $request->salary;
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits;
        $job->responsibility = $request->responsibility;
        $job->qualifications = $request->qualifications;
        $job->keywords = $request->keywords;
        $job->experience = $request->experience;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location;
        $job->company_website = $request->company_website;
        $job->save();

        session()->flash('success', 'Job Added Successfully.');
        return response()->json([
            'status' => true,
            'errors' => [],
        ]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }
}

public function myJobs(){
    $jobs= Job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at', 'DESC')->paginate(10);
    return view('frontend.account.job.my-jobs', compact('jobs'));
}

public function editJob(Request $request, $id){
    $job_types= JobType::orderBy('name', 'ASC')->where('status',1)->get();
    $categories= Category::orderBy('name', 'ASC')->where('status',1)->get();

    $job= Job::where([
        'user_id' => Auth::user()->id,
        'id'=> $id
    ])->first();

    if($job==null){
        abort(404);
    }
    return view('frontend.account.job.edit',compact('categories','job_types','job'));
}

public function updateJob(Request $request, $id){
    $rules = [
        'title' => 'required|min:5|max:200',
        'category' => 'required',
        'jobType' => 'required',
        'vacancy' => 'required|integer',
        'location' => 'required|max:50',
        'description' => 'required',
        'company_name' => 'required|min:3|max:40',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->passes()) {
        $job = Job::find($id);
        $job->title = $request->title;
        $job->category_id = $request->category;
        $job->job_type_id = $request->jobType;
        $job->user_id = Auth::user()->id;
        $job->vacancy = $request->vacancy;
        $job->salary = $request->salary;
        $job->location = $request->location;
        $job->description = $request->description;
        $job->benefits = $request->benefits;
        $job->responsibility = $request->responsibility;
        $job->qualifications = $request->qualifications;
        $job->keywords = $request->keywords;
        $job->experience = $request->experience;
        $job->company_name = $request->company_name;
        $job->company_location = $request->company_location;
        $job->company_website = $request->company_website;
        $job->save();

        session()->flash('success', 'Job Updated Successfully.');
        return response()->json([
            'status' => true,
            'errors' => [],
        ]);
    } else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }
}

public function deleteJob(Request $request){
    $job= Job::where([
        'user_id' => Auth::user()->id,
        'id'=> $request->jobId,
    ])->first();

    if($job==null){
       session()->flash('error', 'Either job has got deleted or not found !!');
       return response()->json([
        'status' => true,
        'errors' => [],
       ]);
    }

    Job::where('id', $request->jobId)->delete();
    session()->flash('success', 'Job Deleted Successfully');
    return response()->json([
     'status' => true,
     
    ]);
}

public function myJobApplications(){
    $jobApplications =JobApplication::where('user_id',Auth::user()->id)
                                    ->with('job','job.jobType','job.applicationCount')
                                    ->orderBy('created_at','DESC')
                                    ->paginate(10);
    return view('frontend.account.job.my-job-applications',compact('jobApplications'));
}

public function removeAppliedJobs(Request $request){
    $jobApplication = JobApplication::where([
        'id' => $request->id, 
        'user_id' => Auth::user()->id
        ])->first();

        if($jobApplication == null){
            session()->flash('error', 'Job Application not Found!!');
            return response()->json([
                'status' => false,
            ]);
        }

    JobApplication::find($request->id)->delete();
    session()->flash('success', 'Job Application Removed Successfully');
    return response()->json([
     'status' => true,
     
    ]);
}


public function savedJobs(){
    $savedJobs =SavedJob::where('user_id',Auth::user()->id)
    ->with('job','job.jobType','job.applicationCount')
    ->orderBy('created_at','DESC')
    ->paginate(10);

return view('frontend.account.job.saved-jobs',compact('savedJobs'));
}

public function removeSavedJobs(Request $request){
    $savedJob = SavedJob::where([
        'id' => $request->id, 
        'user_id' => Auth::user()->id
        ])->first();

        if($savedJob == null){
            session()->flash('error', 'Job Application not Found!!');
            return response()->json([
                'status' => false,
            ]);
        }

    SavedJob::find($request->id)->delete();
    session()->flash('success', ' Saved Job Application Removed Successfully');
    return response()->json([
     'status' => true,
     
    ]);
}


}

