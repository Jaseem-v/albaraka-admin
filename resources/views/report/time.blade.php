@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Time Report</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Time Report</li>
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
                <form action="{{ route('time') }}" method="POST" enctype="multipart/form-data" target="_blank">
                    @csrf
                    @php
                        $month = carbon\carbon::now()->format('m');
                        $year = carbon\carbon::now()->format('Y');
                    @endphp
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <label class="form-label">Select Month</label>
                            <select class="form-control" name="month">
                                <option value="01" {{ old('month',$month=='01' ? 'selected' : '') }}>January</option>
                                <option value="02" {{ old('month',$month=='02' ? 'selected' : '') }}>February</option>
                                <option value="03" {{ old('month',$month=='03' ? 'selected' : '') }}>March</option>
                                <option value="04" {{ old('month',$month=='04' ? 'selected' : '') }}>April</option>
                                <option value="05" {{ old('month',$month=='05' ? 'selected' : '') }}>May</option>
                                <option value="06" {{ old('month',$month=='06' ? 'selected' : '') }}>June</option>
                                <option value="07" {{ old('month',$month=='07' ? 'selected' : '') }}>July</option>
                                <option value="08" {{ old('month',$month=='08' ? 'selected' : '') }}>August</option>
                                <option value="09" {{ old('month',$month=='09' ? 'selected' : '') }}>September</option>
                                <option value="10" {{ old('month',$month=='10' ? 'selected' : '') }}>October</option>
                                <option value="11" {{ old('month',$month=='11' ? 'selected' : '') }}>November</option>
                                <option value="12" {{ old('month',$month=='12' ? 'selected' : '') }}>December</option>
                            </select>
                            @error('month')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-3">
                            <label class="form-label">Select Year</label>
                            <select class="form-control" name="year">
                                @for ($i= 2000; $i<=$year+5; $i++)
                                    <option value="{{ $i }}" {{ old('year',$i==$year ? 'selected' : '') }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('year')
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