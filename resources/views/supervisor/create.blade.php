@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Add Supervisor</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Add Supervisor</li>
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
                <form action="{{ route('supervisor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Supervisor ID *</label>
                            <input type="text" class="form-control" name="supervisor_id" autocomplete="off" value="{{ old('supervisor_id') }}">
                            @error('supervisor_id')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" autocomplete="off" value="{{ old('name') }}">
                            @error('name')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">E-mail *</label>
                            <input type="email" class="form-control" name="email" autocomplete="off" value="{{ old('email') }}">
                            @error('email')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" autocomplete="off" value="{{ old('password') }}">
                            @error('password')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mt-3">
                            <label class="form-label">Select Status *</label>
                            <select class="form-control select" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2 mt-5">
                            <input type="submit" value="Add Supervisor" class="btn btn-primary form-control">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@endsection