<?php

namespace App\Http\Controllers\admin;

use App\Models\Job;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(){
        $users = User::where('role', 'user');
        $userCount = $users->count();

        $jobs =Job::all();
        $jobCount = $jobs->count();

        $applications = JobApplication::all();
        $applicationCount = $applications->count();

        return view('admin.dashboard',compact('userCount', 'jobCount', 'applicationCount'));
    }
}
