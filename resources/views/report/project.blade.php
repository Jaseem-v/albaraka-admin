@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Project Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Project Report</li>
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
                <form action="{{ route('project.print') }}" method="POST" enctype="multipart/form-data" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label class="form-label">From Date</label>
                            <input type="date" class="form-control" name="from_date" autocomplete="off" value="{{ old('from_date') }}">
                            @error('from_date')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">To Date</label>
                            <input type="date" class="form-control" name="to_date" autocomplete="off" value="{{ old('to_date') }}">
                            @error('to_date')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-4 mt-3">
                            <label class="form-label">Project</label>
                            <select class="form-control" name="project">
                                <option value="">select project</option>
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
                        <div class="col-md-2 mt-5">
                            <input type="submit" value="Download Report" class="btn btn-primary form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection