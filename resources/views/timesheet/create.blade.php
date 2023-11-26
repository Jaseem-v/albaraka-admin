@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Timesheet</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Add Timesheet</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mt-3">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Attendance *</label>
                            <select class="form-control select" name="attendance" id="attendance">
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br>
                <div id="present">
                    <form action="{{ route('timesheet.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Select Employee *</label>
                                <select class="form-control select" name="employee">
                                    <option value="">--select--</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Select Project *</label>
                                <select class="form-control select" name="project">
                                    <option value="">--select--</option>
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                @error('project')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Start date *</label>
                                <input type="date" class="form-control" name="start_date" autocomplete="off" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">End date *</label>
                                <input type="date" class="form-control" name="end_date" autocomplete="off" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Start Time *</label>
                                <input type="time" class="form-control" name="start_time" autocomplete="off" value="{{ old('start_time') }}">
                                @error('start_time')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">End Time *</label>
                                <input type="time" class="form-control" name="end_time" autocomplete="off" value="{{ old('end_time') }}">
                                @error('end_time')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Working On *</label>
                                <select class="form-control select" name="working_on">
                                    <option value="workingday">Working Day</option>
                                    <option value="holiday">Holiday</option>
                                </select>
                                @error('working_on')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2 mt-5">
                                <input type="submit" value="Submit" class="btn btn-primary form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div id="absent" style="display: none">
                    <form action="{{ route('leave.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Select Employee *</label>
                                <select class="form-control select" name="employee">
                                    <option value="">--select--</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                    @foreach ($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                    @endforeach
                                </select>
                                @error('employee')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Date *</label>
                                <input type="date" class="form-control" name="date" autocomplete="off" value="{{ old('date') }}">
                                @error('date')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label class="form-label">Leave Reason *</label>
                                <select class="form-control select" name="reason">
                                    <option value="general">General Leave</option>
                                    <option value="medical">Medical Leave</option>
                                    <option value="annual">Annual Leave</option>
                                </select>
                                @error('reason')
                                    <span class="text-danger">{{$message}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2 mt-5">
                                <input type="submit" value="Submit" class="btn btn-primary form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<script>
    $(document).ready(function(){
        $(window).on("load", function() {
            $("#present").show();
            $("#absent").hide();
        });

        $("#attendance").on("change", function() {
            var val = $("#attendance").val();
            if (val == 'present') {
                $("#present").show();
                $("#absent").hide();
            }
            else if(val == 'absent'){
                $("#present").hide();
                $("#absent").show();
            }
            else {
                $("#present").show();
                $("#absent").hide();
            }
        })
    });
</script>

@endsection