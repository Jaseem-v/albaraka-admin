@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Change Password</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Change Password</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Change Password</h4>

                <form method="POST" action="{{ route('password.change', Auth::user()->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="row"> 
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">New Password</label>
                                <input type="password" name="password" value="" class="form-control" id="formrow-email-input">
                                @error('password')
                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="formrow-email-input" class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" value="" class="form-control" id="formrow-email-input">
                                @error('password_confirmation')
                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-md">Update</button>
                    </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
    </div>
</div>

@endsection
