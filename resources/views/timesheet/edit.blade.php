@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Timesheet</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Edit Timesheet</li>
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
                <form action="{{ route('timesheet.update', $timesheet->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Select Employee *</label>
                            <select class="form-control select" name="employee">
                                <option value="">--select--</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}" {{ old('employee',$employee->id==$timesheet->emp_id?'selected':'') }}>{{ $employee->name }}</option>
                                @endforeach
                                @foreach ($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}" {{ old('employee',$supervisor->id==$timesheet->emp_id?'selected':'') }}>{{ $supervisor->name }}</option>
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
                                    <option value="{{ $project->id }}" {{ old('project',$project->id==$timesheet->prj_id?'selected':'') }}>{{ $project->name }}</option>
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
                            <input type="date" class="form-control" name="start_date" autocomplete="off" value="{{ $timesheet->start_date }}">
                            @error('start_date')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">End date *</label>
                            <input type="date" class="form-control" name="end_date" autocomplete="off" value="{{ $timesheet->end_date }}">
                            @error('end_date')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Start Time *</label>
                            <input type="time" class="form-control" name="start_time" autocomplete="off" value="{{ $timesheet->start_time }}">
                            @error('start_time')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">End Time *</label>
                            <input type="time" class="form-control" name="end_time" autocomplete="off" value="{{ $timesheet->end_time }}">
                            @error('end_time')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Working On *</label>
                            <select class="form-control select" name="working_on">
                                <option value="workingday" {{ old('working_on',$timesheet->working_on == 'workingday'? 'selected' : '') }}>Working Day</option>
                                <option value="holiday" {{ old('working_on',$timesheet->working_on == 'holiday'? 'selected' : '') }}>Holiday</option>
                            </select>
                            @error('working_on')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mt-5">
                            <input type="submit" value="Update" class="btn btn-primary form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@endsection