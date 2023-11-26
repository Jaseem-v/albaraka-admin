<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin') {
        $projects = Project::get();
        return view('project.index', compact('projects'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required|integer'
        ]);

        $project = new Project();
        $project->name = $request->name;
        $project->status = $request->status;
        $result = $project->save();

        if ($result) {
            Toastr::success('project added', 'success');
            return redirect()->back();
        }
        else {
            Toastr::error('project failed to add', 'failed');
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required|integer'
        ]);
        
        $project = Project::findOrFail($id);
        $project->name = $request->name;
        $project->status = $request->status;
        $result = $project->save();

        if ($result) {
            Toastr::success('project updated', 'success');
            return redirect()->back();
        }
        else {
            Toastr::error('project failed to update', 'failed');
            return redirect()->back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
