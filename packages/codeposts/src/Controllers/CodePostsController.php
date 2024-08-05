<?php

namespace Leo\CodePosts\Controllers;

use Leo\CodePosts\Models\CodePosts;
use Illuminate\Http\Request;
use Inertia\Inertia;
class CodePostsController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Code/Code');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Topics $topics)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topics $topics)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topics $topics)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topics $topics)
    {
        //
    }
}
