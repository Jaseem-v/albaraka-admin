<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\Project;
use App\Models\TimeSheet;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PdfReport;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sum;

class ReportController extends Controller
{
    public function report()
    {
        $employees = User::where('role', 'employee')->where('active', 1)->get();
        $supervisors = User::where('role', 'supervisor')->where('active', 1)->get();
        return view('report.index', compact('employees', 'supervisors'));
    }

    public function print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $employee = $request->employee;

        // Report title
        $title = 'Employee Work Report';

        // For displaying filters description on header
        if ($fromDate && $toDate && $employee) {
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate
            ];
            $queryBuilder = TimeSheet::whereBetween('start_date', [$fromDate, $toDate])
                ->whereIn('emp_id', $employee)->orderBy('start_date', 'DESC');
        } elseif ($fromDate && $toDate) {
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate
            ];
            $queryBuilder = TimeSheet::whereBetween('start_date', [$fromDate, $toDate])->orderBy('start_date', 'DESC');
        } elseif ($fromDate && $employee != []) {
            $meta = [
                'Date' => 'From ' . $fromDate
            ];
            $queryBuilder = TimeSheet::where('start_date', '>=', $fromDate)->whereIn('emp_id', $employee)->orderBy('start_date', 'DESC');
        } elseif ($toDate && $employee != []) {
            $meta = [
                'Date' => 'To ' . $toDate,
            ];
            $queryBuilder = TimeSheet::where('start_date', '<=', $toDate)->whereIn('emp_id', $employee)->orderBy('start_date', 'DESC');
        } elseif ($fromDate) {
            $meta = [
                'Date' => 'From ' . $fromDate
            ];
            $queryBuilder = TimeSheet::where('start_date', '>=', $fromDate)->orderBy('start_date', 'DESC');
        } elseif ($toDate) {
            $meta = [
                'Date' => 'To ' . $toDate
            ];
            $queryBuilder = TimeSheet::where('start_date', '<=', $toDate)->orderBy('start_date', 'DESC');
        } elseif ($employee != []) {
            $meta = [
                'Date' => 'ALL',
            ];
            $queryBuilder = TimeSheet::whereIn('emp_id', $employee)->orderBy('start_date', 'DESC');
        } else {
            $meta = [
                'Date' => 'ALL'
            ];
            $queryBuilder = TimeSheet::orderBy('start_date', 'DESC');
        }

        // Set Column to be displayed
        $columns = [
            'Employee' => function ($timesheet) {
                return $timesheet->employee->name;
            },
            'Date' => function ($timesheet) {
                if ($timesheet->start_date == $timesheet->end_date) {
                    return Carbon::parse($timesheet->start_date)->format('d-m-Y');
                } else {
                    return Carbon::parse($timesheet->start_date)->format('d-m-Y') . ' to ' . Carbon::parse($timesheet->end_date)->format('d-m-Y');
                }
            },
            'Project' => function ($timesheet) {
                return $timesheet->project->name;
            },
            'Entered By' => function ($timesheet) {
                return $timesheet->supervisor->name;
            },
            'Work On' => function ($timesheet) {
                if ($timesheet->working_on == 'workingday') {
                    return 'Working Day';
                } elseif ($timesheet->working_on == 'holiday') {
                    return 'Holiday';
                }
            },
            'Hours' => function ($timesheet) {
                if ($timesheet->working_on == 'workingday') {

                    return $timesheet->hour;
                } elseif ($timesheet->working_on == 'holiday') {

                    return '0.00';
                }
            },
            'Over Time' => function ($timesheet) {
                if ($timesheet->working_on == 'workingday') {

                    return $timesheet->overtime;
                } elseif ($timesheet->working_on == 'holiday') {

                    return ($timesheet->hour + $timesheet->overtime);
                }
            }
        ];

        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            // Limit record to be showed
            //->limit(20)
            // other available method: store('path/to/file.pdf') to save to disk, download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            ->stream();
    }

    public function project()
    {
        $projects = Project::where('status', 1)->get();
        return view('report.project', compact('projects'));
    }

    public function project_report(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $project = $request->project;

        $title = 'Project Time Report';

        if ($fromDate && $toDate && $project) {
            $prj = Project::findOrFail($project);
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate,
                'Project' => $prj->name
            ];
        } elseif ($fromDate && $toDate) {
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate,
                'Project' => 'ALL'
            ];
        } elseif ($fromDate && $project) {
            $prj = Project::findOrFail($project);
            $meta = [
                'Date' => 'From ' . $fromDate,
                'Project' => $prj->name
            ];
        } elseif ($toDate && $project) {
            $prj = Project::findOrFail($project);
            $meta = [
                'Date' => 'To ' . $toDate,
                'Project' => $prj->name
            ];
        } elseif ($project) {
            $prj = Project::findOrFail($project);
            $meta = [
                'Date' => 'ALL',
                'Project' => $prj->name
            ];
        } elseif ($fromDate) {
            $meta = [
                'Date' => 'From ' . $fromDate,
                'Project' => 'ALL'
            ];
        } elseif ($toDate) {
            $meta = [
                'Date' => 'To ' . $toDate,
                'Project' => 'ALL'
            ];
        } else {
            $meta = [
                'Date' => 'ALL',
                'Project' => 'ALL'
            ];
        }

        $queryBuilder = DB::table('projects as p')
            ->join('time_sheets as t', 'p.id', '=', 't.prj_id')
            ->where('t.start_date', '>=', $fromDate)
            ->where('t.end_date', '<=', $toDate)
            ->groupBy('p.id', 'p.name');

        if ($project) {
            $queryBuilder->where('p.id', $project);
        }

        $queryBuilder->select([
            'p.id as project_id',
            'p.name as project_name',
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(t.hour, "%H.%i")))) as total_hours'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(STR_TO_DATE(t.overtime, "%H.%i")))) as total_over_time'),
        ]);

        $columns = [
            'Project' => 'project_name',
            'Total Hours' => 'total_hours',
            'Total Overtime' => 'total_over_time'
        ];

        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            ->editColumn('Total Hours', [
                'displayAs' => function ($result) {
                    return substr($result->total_hours, 0, -3);
                },
                'class' => 'left'
            ])
            ->editColumn('Total Overtime', [
                'displayAs' => function ($result) {
                    return substr($result->total_over_time, 0, -3);
                },
                'class' => 'left'
            ])
            ->stream();
    }


    public function leave_report()
    {
        $employees = User::where('role', 'employee')->where('active', 1)->get();
        $supervisors = User::where('role', 'supervisor')->where('active', 1)->get();
        return view('report.leave', compact('employees', 'supervisors'));
    }

    public function leave_report_print(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $employee = $request->employee;

        // Report title
        $title = 'Leave Report';

        // For displaying filters description on header
        if ($fromDate && $toDate && $employee) {
            $emp_name = User::findOrFail($employee);
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate,
                'Employee' => $emp_name->name
            ];
            $queryBuilder = Leave::whereBetween('date', [$fromDate, $toDate])->where('emp_id', $employee)->orderBy('date', 'DESC');
        } elseif ($fromDate && $toDate) {
            $meta = [
                'Date' => $fromDate . ' To ' . $toDate,
                'Employee' => 'ALL'
            ];
            $queryBuilder = Leave::whereBetween('date', [$fromDate, $toDate])->orderBy('date', 'DESC');
        } elseif ($fromDate && $employee) {
            $emp_name = User::findOrFail($employee);
            $meta = [
                'Date' => 'From ' . $fromDate,
                'Employee' => $emp_name->name
            ];
            $queryBuilder = Leave::where('date', '>=', $fromDate)->where('emp_id', $employee)->orderBy('date', 'DESC');
        } elseif ($toDate && $employee) {
            $emp_name = User::findOrFail($employee);
            $meta = [
                'Date' => 'To ' . $toDate,
                'Employee' => $emp_name->name
            ];
            $queryBuilder = Leave::where('date', '<=', $toDate)->where('emp_id', $employee)->orderBy('date', 'DESC');
        } elseif ($fromDate) {
            $meta = [
                'Date' => 'From ' . $fromDate,
                'Employee' => 'ALL'
            ];
            $queryBuilder = Leave::where('date', '>=', $fromDate)->orderBy('date', 'DESC');
        } elseif ($toDate) {
            $meta = [
                'Date' => 'To ' . $toDate,
                'Employee' => 'ALL'
            ];
            $queryBuilder = Leave::where('date', '<=', $toDate)->orderBy('date', 'DESC');
        } elseif ($employee) {
            $emp_name = User::findOrFail($employee);
            $meta = [
                'Date' => 'ALL',
                'Employee' => $emp_name->name
            ];
            $queryBuilder = Leave::where('emp_id', $employee)->orderBy('date', 'DESC');
        } else {
            $meta = [
                'Date' => 'ALL',
                'Employee' => 'ALL'
            ];
            $queryBuilder = Leave::orderBy('date', 'DESC');
        }

        // Set Column to be displayed
        $columns = [
            'Date' => function ($leave) {
                return Carbon::parse($leave->date)->format('d-m-Y');
            },

            'Employee' => function ($leave) {
                return $leave->employee->name;
            },

            'Reason' => function ($leave) {
                if ($leave->reason == 'general') {
                    return 'General Leave';
                } elseif ($leave->reason == 'medical') {
                    return 'Medical Leave';
                } elseif ($leave->reason == 'annual') {
                    return 'Annual Leave';
                }
            }
        ];

        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            // Limit record to be showed
            //->limit(20)
            // other available method: store('path/to/file.pdf') to save to disk, download('filename') to download pdf / make() that will producing DomPDF / SnappyPdf instance so you could do any other DomPDF / snappyPdf method such as stream() or download()
            ->stream();
    }

    public function time_index()
    {
        return view('report.time');
    }

    public function time(Request $request)
    {
        $this->validate($request, [
            'month' => 'required',
            'year' => 'required'
        ]);

        $month = $request->month;
        $year = $request->year;

        // Report title
        $title = 'Employee Time Report';

        // For displaying filters description on header
        $meta = [];

        $queryBuilder = TimeSheet::select(
            'emp_id',
            DB::raw("EXTRACT(YEAR_MONTH FROM start_date) as month"),
            DB::raw("SUM(hour) as hour_total"),
            DB::raw("SUM(overtime) as overtime_total")
        )
            ->whereMonth('start_date', $month)->whereYear('start_date', $year)
            ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"))
            ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"), 'DESC');

        // Set Column to be displayed
        $columns = [
            'Employee' => function ($timesheet) {
                return $timesheet->employee->name;
            },
            'Date' => function ($timesheet) {
                $monthNum  = substr($timesheet->month, -2, 2);
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');

                return $monthName . ' - ' . substr($timesheet->month, 0, 4);
            },
            'Hours' => function ($timesheet) {
                return $timesheet->hour_total;
            },
            'Over Time' => function ($timesheet) {
                return $timesheet->overtime_total;
            },
            'Holiday' => '',

            'Leave' => ''
        ];

        // Generate Report with flexibility to manipulate column class even manipulate column value (using Carbon, etc).
        return PdfReport::of($title, $meta, $queryBuilder, $columns)
            ->editColumn('Hours', [
                'displayAs' => function ($result) {
                    return TimeSheet::select(
                        'emp_id',
                        DB::raw("EXTRACT(YEAR_MONTH FROM start_date) as month"),
                        DB::raw("SUM(hour) as hour_total"),
                        DB::raw("SUM(overtime) as overtime_total")
                    )
                        ->where('emp_id', $result->emp_id)->where('working_on', 'workingday')
                        ->whereMonth('start_date', substr($result->month, -2, 2))->whereYear('start_date', substr($result->month, 0, 4))
                        ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"))
                        ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"), 'DESC')->sum('hour');
                }
            ])
            ->editColumn('Over Time', [
                'displayAs' => function ($result) {
                    return TimeSheet::select(
                        'emp_id',
                        DB::raw("EXTRACT(YEAR_MONTH FROM start_date) as month"),
                        DB::raw("SUM(hour) as hour_total"),
                        DB::raw("SUM(overtime) as overtime_total")
                    )
                        ->where('emp_id', $result->emp_id)->where('working_on', 'workingday')
                        ->whereMonth('start_date', substr($result->month, -2, 2))->whereYear('start_date', substr($result->month, 0, 4))
                        ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"))
                        ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"), 'DESC')->sum('overtime');
                }
            ])
            ->editColumn('Holiday', [
                'displayAs' => function ($result) {
                    $first = TimeSheet::select(
                        'emp_id',
                        DB::raw("EXTRACT(YEAR_MONTH FROM start_date) as month"),
                        DB::raw("SUM(hour) as hour_total"),
                        DB::raw("SUM(overtime) as overtime_total")
                    )
                        ->where('emp_id', $result->emp_id)->where('working_on', 'holiday')
                        ->whereMonth('start_date', substr($result->month, -2, 2))->whereYear('start_date', substr($result->month, 0, 4))
                        ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"))
                        ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"), 'DESC')->sum('hour');

                    $second = TimeSheet::select(
                        'emp_id',
                        DB::raw("EXTRACT(YEAR_MONTH FROM start_date) as month"),
                        DB::raw("SUM(hour) as hour_total"),
                        DB::raw("SUM(overtime) as overtime_total")
                    )
                        ->where('emp_id', $result->emp_id)->where('working_on', 'holiday')
                        ->whereMonth('start_date', substr($result->month, -2, 2))->whereYear('start_date', substr($result->month, 0, 4))
                        ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"))
                        ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM start_date)"), 'DESC')->sum('overtime');

                    return $first + $second;
                }
            ])
            ->editColumn('Leave', [
                'displayAs' => function ($result) {
                    return Leave::select('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM date) as month"))
                        ->where('emp_id', $result->emp_id)
                        ->whereMonth('date', substr($result->month, -2, 2))->whereYear('date', substr($result->month, 0, 4))
                        ->groupBy('emp_id', DB::raw("EXTRACT(YEAR_MONTH FROM date)"))
                        ->orderBy(DB::raw("EXTRACT(YEAR_MONTH FROM date)"), 'DESC')->count();
                }
            ])
            ->groupBy('Date')
            ->stream();
    }
}
