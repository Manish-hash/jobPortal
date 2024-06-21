<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\User;
use App\Models\JobType;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Mail\JobNotificationEmail;
use App\Models\SavedJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $job_types = JobType::where('status', 1)->get();
        $categories = Category::where('status', 1)->get();

        $jobs = Job::where('status', 1);
        //Search Using Keywords
        if (!empty($request->keyword)) {
            $jobs = $jobs->where(function ($query) use ($request) {
                $query->orWhere('title', 'like', '%' . $request->keyword . '%');
                $query->orWhere('keywords', 'like', '%' . $request->keyword . '%');
            });
        }

        //search using location
        if (!empty($request->location)) {
            $jobs = $jobs->where('location', 'like', '%' . $request->location . '%');
        }

        //Search using Category
        if (!empty($request->category)) {
            $jobs = $jobs->where('category_id', $request->category);
        }


        //search using Job Type
        $jobTypeArray = [];
        if (!empty($request->jobType)) {
            // Check if $request->jobType is a string
            if (is_string($request->jobType)) {
                $jobTypeArray = explode(',', $request->jobType);
            } elseif (is_array($request->jobType)) {
                $jobTypeArray = $request->jobType;
            }
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArray);
        }

        //Search using Experience
        if (!empty($request->experience)) {
            $jobs = $jobs->where('experience', $request->experience);
        }

        //Sort with latest and older jobs
        if (!empty($request->sort) && $request->sort == 1) {
            $jobs = $jobs->orderBy('created_at', 'AESC');
        } else {
            $jobs = $jobs->orderBy('created_at', 'DESC');
        }



        $jobs = $jobs->with('jobType')->orderBy('created_at', 'DESC')->paginate(9);
        return view('frontend.jobs', compact('categories', 'job_types', 'jobs', 'jobTypeArray'));
    }


    public function detail($id)
    {
        $job = Job::where([
            'id' => $id, 'status' => 1
        ])->first();

        if ($job == null) {
            abort(404);
        }

        $count = 0;
        if(Auth::user()){
            $count = SavedJob::where([
                'user_id' => Auth::user()->id,
                'job_id' =>$id
            ])->count();
        }

        //Fetch Application

        $applications = JobApplication::where('job_id',$id)->with('user')->get();

        return view('frontend.jobDetail', [
            'job' => $job,
            'count' => $count,
            'applications' => $applications
        
        ]);


       

        return view('frontend.jobDetail', compact('job','count'));
    }

    public function applyJob(Request $request)
    {
        $id = $request->id;
        $job = Job::where('id', $id)->first();

        if ($job == null) {
            session()->flash('error', 'Job does not exists');
            return response()->json([
                'status' => false,
                'message' => 'Job doesnot exists '
            ]);
        }
        //you cannot apply on your own job

        $employer_id = $job->user_id;

        if ($employer_id == Auth::user()->id) {
            session()->flash('error', 'You cannot apply on your own job.');
            return response()->json([
                'status' => false,
                'message' => 'JYou cannot apply on your own job. '
            ]);
        }

        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $employer_id;
        $application->applied_date = now();
        $application->save();

        //Send Notification Email to  Employer
        $employer = User::where('id', $employer_id)->first();
        $mailData = [
            'employer' =>  $employer,
            'user' => Auth::user(),
           'job' => $job,
            
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));


        session()->flash('success', 'You have successfully applied for this job');
        return response()->json([
            'status' => true,
            'message' => 'Job application successful.'
        ]);
    }

    public function saveJob(Request $request){
        $id= $request->id;
        $job = Job::find($id);

        if($job == null){
            session()->flash('error','Job not found');

            return response()->json([

            ]);
        }

        //Check if user already save the job
       $count = SavedJob::where([
            'user_id' => Auth::user()->id,
            'job_id' =>$id
        ])->count();

        if($count>0){
            session()->flash('error', 'You already saved this job.');

            return response()->json([
                'status' => false,
            ]);
        }

        $savedJob = new SavedJob;
        $savedJob->job_id = $id;
        $savedJob->user_id = Auth::user()->id;
        $savedJob->save();

        session()->flash('success', 'You have successfully saved the job.');

        return response()->json([
            'status' => true,
        ]);


    }
}
