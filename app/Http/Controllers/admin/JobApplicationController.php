<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(){
    $applications = JobApplication::orderBy('created_at','DESC')
                                    ->with('job','user','employer')
                                    ->paginate(8);
    return view('admin.jobApplications.jobApplication',compact('applications'));
}

public function destroy(Request $request){
    $id = $request->id;
    $application = JobApplication::find($id);
    if($application == null){
        session()->flash('error', 'Job Not Found');
        return response()->json([
            'status' => false,
            'errors' =>[],
        ]);
    }

    $application->delete();
    session()->flash('success', 'JOb Application Deleted Successfully');
    return response()->json([
        'status' => true,
       
    ]);
}

}
