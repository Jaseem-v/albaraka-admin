<?php

namespace App\Http\Controllers;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->role == 'admin') {
        $employees = User::where('role','employee')->get();
        return view('employee.index', compact('employees'));
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
        return view('employee.create');
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
            'employee_id' => 'required|unique:users,sup_id',
            'name' => 'required|string',
            'status' => 'required|integer'
        ]);

        $employee = new User();
        $employee->sup_id = $request->employee_id;
        $employee->name = $request->name;
        $employee->role = 'employee';
        $employee->active = $request->status;
        $result = $employee->save();

        if ($result) {
            Toastr::success('employee added', 'success');
            return redirect()->route('employee.index');
        }
        else {
            Toastr::error('employee failed to add', 'failed');
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
        $employee = User::findOrFail($id);
        return view('employee.edit', compact('employee'));
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
            'status' => 'required|integer'
        ]);
        
        $employee = User::findOrFail($id);
        $employee->name = $request->name;
        $employee->active = $request->status;
        $result = $employee->save();

        if ($result) {
            Toastr::success('employee updated', 'success');
            return redirect()->route('employee.index');
        }
        else {
            Toastr::error('employee failed to update', 'failed');
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
