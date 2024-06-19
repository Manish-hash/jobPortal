<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //This method shows the home page
    public function index(){
        $categories = Category::where('status',1)->orderBy('name','ASC')->take(8)->get();
        $featuredjobs = Job::where('status',1)
                            ->orderBy('created_at', 'DESC')
                            ->with('jobType')
                            ->where('isFeatured',1)->take(6)->get();

        $latestjobs = Job::where('status',1)
                            ->orderBy('created_at', 'DESC')
                            ->with('jobType')
                            ->take(6)->get();
        return view('frontend.home',compact('categories','featuredjobs','latestjobs'));
    }
}
