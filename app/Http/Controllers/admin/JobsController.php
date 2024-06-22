<?php

namespace App\Http\Controllers\admin;

use App\Models\Job;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobsController extends Controller
{
    public function index(){
        $jobs = Job::orderBy('created_at','DESC')->with('user','applicationCount')->paginate(8);
        return view('admin.jobs.job',compact('jobs'));
    }

    public function edit($id){
        $job = Job::findOrFail($id);
        $categories = Category::orderBy('name','ASC')->get();
        $job_types = JobType::orderBy('name','ASC')->get();
        return view('admin.jobs.edit',compact('job','categories', 'job_types'));
    }

    public function update(Request $request, $id){
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
            $job->isFeatured = (!empty($request->isFeatured)) ? $request->isFeatured : 0;
            $job->status = $request->status;
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

    public function destroy(Request $request){
        $id = $request->id;
        $job = Job::find($id);
        if($job == null){
            session()->flash('error', 'Job Not Found');
            return response()->json([
                'status' => false,
                'errors' =>[],
            ]);
        }

        $job->delete();
        session()->flash('success', 'JOb Deleted Successfully');
        return response()->json([
            'status' => true,
           
        ]);
    }
}
