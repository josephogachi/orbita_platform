<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobController extends Controller
{
    // 1. Shows the list of all available jobs
    public function index()
    {
        $jobs = JobPosting::where('is_published', true)
            ->where(function ($query) {
                $query->whereNull('closing_date')
                      ->orWhere('closing_date', '>=', now());
            })
            ->latest()
            ->get();

        return view('jobs.index', compact('jobs'));
    }

    // 2. Shows the specific job details and the application form
    public function show($slug)
    {
        $job = JobPosting::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        return view('jobs.show', compact('job'));
    }

    // 3. Processes the CV upload and saves the application
    public function apply(Request $request, $slug)
    {
        $job = JobPosting::where('slug', $slug)->where('is_published', true)->firstOrFail();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // Max 5MB
            'portfolio_url' => 'nullable|url|max:255',
            'cover_letter' => 'nullable|string'
        ]);

        // Securely store the CV in the storage/app/public/resumes folder
        $resumePath = $request->file('resume')->store('resumes', 'public');

        JobApplication::create([
            'job_posting_id' => $job->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'resume_path' => $resumePath,
            'portfolio_url' => $request->portfolio_url,
            'cover_letter' => $request->cover_letter,
        ]);

        return back()->with('success', 'Your application has been submitted successfully! Our HR team will review it shortly.');
    }
}