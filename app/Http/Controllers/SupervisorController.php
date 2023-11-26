<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin') {
        $users = User::where('role','supervisor')->get();
        return view('supervisor.index', compact('users'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role == 'admin') {
        return view('supervisor.create');
        }
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
            'supervisor_id' => 'required|unique:users,sup_id',
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required',
            'status' => 'required|integer'
        ]);

        $user = new User();
        $user->sup_id = $request->supervisor_id;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->active = $request->status;
        $user->role = 'supervisor';
        $result = $user->save();

        if ($result) {
            Toastr::success('supervisor added', 'success');
            return redirect()->route('supervisor.index');
        }
        else {
            Toastr::error('supervisor failed to add', 'failed');
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
        if(Auth::user()->role == 'admin') {
        $user = User::findOrFail($id);
        return view('supervisor.edit', compact('user'));
        }
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
            'name' => 'required|string',
            'email' => 'required|email',
            'status' => 'required|integer'
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        else {
            $user->password = $user->password;
        }
        $user->active = $request->status;
        $result = $user->save();

        if ($result) {
            Toastr::success('supervisor updated', 'success');
            return redirect()->route('supervisor.index');
        }
        else {
            Toastr::error('supervisor failed to update', 'failed');
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

    public function disable(Request $request, $id)
    {
        $user =User::findOrFail($id);
        $user->entry_limit = 0;
        $result = $user->save();

        if ($result) {
            Toastr::success('Timesheet entry limit option Disabled', 'success');
            return redirect()->back();
        }
        else {
            Toastr::error('something wrong!!', 'failed');
            return redirect()->back();
        }
    }

    public function enable(Request $request, $id)
    {
        $user =User::findOrFail($id);
        $user->entry_limit = 1;
        $result = $user->save();

        if ($result) {
            Toastr::success('Timesheet entry limit option Enabled', 'success');
            return redirect()->back();
        }
        else {
            Toastr::error('something wrong!!', 'failed');
            return redirect()->back();
        }
    }
}
