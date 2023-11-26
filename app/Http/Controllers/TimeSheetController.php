<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Project;
use App\Models\TimeSheet;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $timesheets = TimeSheet::with('supervisor', 'employee', 'project')->orderBy('start_date','DESC')->get();

        }
        elseif (Auth::user()->role == 'supervisor') {
            $timesheets = TimeSheet::with('supervisor', 'employee', 'project')->where('entered_by',Auth::user()->id)->orderBy('start_date','DESC')->get();
        }
        
        return view('timesheet.index', compact('timesheets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role == 'supervisor') {
        $employees = User::where('role','employee')->where('active',1)->get();
        $supervisors = User::where('role','supervisor')->where('active',1)->get();
        $projects = Project::where('status',1)->get();
        return view('timesheet.create', compact('projects', 'employees', 'supervisors'));
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
            'employee' => 'required',
            'project' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'working_on' => 'required'
        ]);

        $employee = $request->employee;
        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('m-Y');
        $start_month = Carbon::parse($request->start_date)->format('m-Y');
        $diff = (new Carbon($today))->diff(new Carbon($request->start_date))->format('%a');
        $start = Carbon::parse($request->start_date . $request->start_time);
        $end = Carbon::parse($request->end_date . $request->end_time);
        $timediff = (new Carbon($start))->diff(new Carbon($end))->format('%h.%i');
        $hour = Carbon::createFromTime(8, 0, 0);
        $formathour = Carbon::parse($hour)->format('h.i');
        if ($timediff > 8) {
            $normal = '8.00';
            $bal = $bal = $timediff - $formathour;
            $formatbal = number_format($bal,2);
        }
        else {
            $normal = $timediff;
            $formatbal = '0.00';
        }

        $same_start = TimeSheet::where('emp_id',$employee)->whereBetween('start_date',[$request->start_date,$request->end_date])
            ->WhereBetween('end_date',[$request->start_date,$request->end_date])
            ->where([['start_time','<=',$request->start_time],['end_time','>=',$request->start_time]])
            //->Where([['start_time','<=',$request->end_time],['end_time','>=',$request->end_time]])
            ->first();

        $same_end = TimeSheet::where('emp_id',$employee)->whereBetween('start_date',[$request->start_date,$request->end_date])
            ->WhereBetween('end_date',[$request->start_date,$request->end_date])
            //->where([['start_time','<=',$request->start_time],['end_time','>=',$request->start_time]])
            ->Where([['start_time','<=',$request->end_time],['end_time','>=',$request->end_time]])
            ->first();

        if ($same_start || $same_end) {
            Toastr::error('Duplicate entry detected', 'Failed');
            return redirect()->back();
        }
        else {
            if (Auth::user()->entry_limit == 1) {
                if ($diff > 2 || ($month != $start_month)) {
                    Toastr::error('48 Hours entry limit reached. Please contact admin!!', 'Failed');
                    return redirect()->back();
                }
                else {
                    $timesheet = new TimeSheet();
                    $timesheet->emp_id = $employee;
                    $timesheet->prj_id = $request->project;
                    $timesheet->start_date_time = $request->start_date.' '.$request->start_time;
                    $timesheet->end_date_time = $request->end_date.' '.$request->end_time;
                    $timesheet->start_date = $request->start_date;
                    $timesheet->end_date = $request->end_date;
                    $timesheet->start_time = $request->start_time;
                    $timesheet->end_time = $request->end_time;
                    $timesheet->working_on = $request->working_on;
                    $timesheet->hour = $normal;
                    $timesheet->overtime = $formatbal;
                    $timesheet->entered_by = Auth::user()->id;
                    $result = $timesheet->save();

                    if ($result) {
                        Toastr::success('timesheet added', 'success');
                        return redirect()->route('timesheet.index');
                    }
                    else {
                        Toastr::error('timesheet failed to add', 'failed');
                        return redirect()->back();
                    }
                }
            }
            elseif (Auth::user()->entry_limit == 0) {
                $timesheet = new TimeSheet();
                $timesheet->emp_id = $employee;
                $timesheet->prj_id = $request->project;
                $timesheet->start_date_time = $request->start_date.' '.$request->start_time;
                $timesheet->end_date_time = $request->end_date.' '.$request->end_time;
                $timesheet->start_date = $request->start_date;
                $timesheet->end_date = $request->end_date;
                $timesheet->start_time = $request->start_time;
                $timesheet->end_time = $request->end_time;
                $timesheet->working_on = $request->working_on;
                $timesheet->hour = $normal;
                $timesheet->overtime = $formatbal;
                $timesheet->entered_by = Auth::user()->id;
                $result = $timesheet->save();

                if ($result) {
                    Toastr::success('timesheet added', 'success');
                    return redirect()->route('timesheet.index');
                }
                else {
                    Toastr::error('timesheet failed to add', 'failed');
                    return redirect()->back();
                }
            }
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
        $timesheet = TimeSheet::findOrFail($id);
        $employees = User::where('role','employee')->where('active',1)->get();
        $supervisors = User::where('role','supervisor')->where('active',1)->get();
        $projects = Project::where('status',1)->get();
        return view('timesheet.edit', compact('timesheet', 'employees', 'supervisors', 'projects'));
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
            'employee' => 'required',
            'project' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'working_on' => 'required'
        ]);

        $employee = $request->employee;
        $today = Carbon::now()->format('Y-m-d');
        $month = Carbon::now()->format('m-Y');
        $start_month = Carbon::parse($request->start_date)->format('m-Y');
        $diff = (new Carbon($today))->diff(new Carbon($request->start_date))->format('%a');
        $start = Carbon::parse($request->start_date . $request->start_time);
        $end = Carbon::parse($request->end_date . $request->end_time);
        $timediff = (new Carbon($start))->diff(new Carbon($end))->format('%h.%i');
        $hour = Carbon::createFromTime(8, 0, 0);
        $formathour = Carbon::parse($hour)->format('h.i');
        if ($timediff > 8) {
            $normal = '8.00';
            $bal = $bal = $timediff - $formathour;
            $formatbal = number_format($bal,2);
        }
        else {
            $normal = $timediff;
            $formatbal = '0.00';
        }
        

        if (Auth::user()->entry_limit == 1) {
            if ($diff > 2 || ($month != $start_month)) {
                Toastr::error('48 Hours entry limit reached. Please contact admin!!', 'Failed');
                return redirect()->back();
            }
            else {
                $timesheet = TimeSheet::findOrFail($id);
                $timesheet->emp_id = $employee;
                $timesheet->prj_id = $request->project;
                $timesheet->start_date_time = $request->start_date.' '.$request->start_time;
                $timesheet->end_date_time = $request->end_date.' '.$request->end_time;
                $timesheet->start_date = $request->start_date;
                $timesheet->end_date = $request->end_date;
                $timesheet->start_time = $request->start_time;
                $timesheet->end_time = $request->end_time;
                $timesheet->working_on = $request->working_on;
                $timesheet->hour = $normal;
                $timesheet->overtime = $formatbal;
                $result = $timesheet->save();

                if ($result) {
                    Toastr::success('timesheet updated', 'success');
                    return redirect()->route('timesheet.index');
                }
                else {
                    Toastr::error('timesheet failed to update', 'failed');
                    return redirect()->back();
                }
            }
        }
        elseif (Auth::user()->entry_limit == 0) {
            $timesheet = TimeSheet::findOrFail($id);
            $timesheet->emp_id = $employee;
            $timesheet->prj_id = $request->project;
            $timesheet->start_date_time = $request->start_date.' '.$request->start_time;
            $timesheet->end_date_time = $request->end_date.' '.$request->end_time;
            $timesheet->start_date = $request->start_date;
            $timesheet->end_date = $request->end_date;
            $timesheet->start_time = $request->start_time;
            $timesheet->end_time = $request->end_time;
            $timesheet->working_on = $request->working_on;
            $timesheet->hour = $normal;
            $timesheet->overtime = $formatbal;
            $result = $timesheet->save();

            if ($result) {
                Toastr::success('timesheet updated', 'success');
                return redirect()->route('timesheet.index');
            }
            else {
                Toastr::error('timesheet failed to update', 'failed');
                return redirect()->back();
            }
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

    public function leave_save(Request $request)
    {
        $this->validate($request, [
            'employee' => 'required',
            'date' => 'required|date',
            'reason' => 'required'
        ]);

        $employee = $request->employee;

        $leave = new Leave();
        $leave->emp_id = $employee;
        $leave->date = $request->date;
        $leave->reason = $request->reason;
        $status = $leave->save();

        if ($status) {
            Toastr::success('Leave applied', 'success');
            return redirect()->route('timesheet.index');
        }
        else {
            Toastr::error('Leave failed to apply', 'failed');
            return redirect()->back();
        }
    }
}
