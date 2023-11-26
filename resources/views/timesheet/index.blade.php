@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Timesheets List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Timesheets List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->
@if (Auth::user()->role == 'supervisor')
<div class="card-header py-3">
    <a href="{{ route('timesheet.create') }}" class="btn btn-primary btn-sm"> <i class="fas fa-plus"> </i> Add Timesheet</a>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Date</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Project</th>
                                <th class="align-middle">Start Time</th>
                                <th class="align-middle">End Time</th>
                                <th class="align-middle">Entered By</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($timesheets as $timesheet)
                            <tr>
                                <td>
                                    @if ($timesheet->start_date == $timesheet->end_date)
                                        {{ Carbon\carbon::parse($timesheet->start_date)->format('d-m-Y') }}
                                    @else
                                        {{ Carbon\carbon::parse($timesheet->start_date)->format('d-m-Y') }} to {{ Carbon\carbon::parse($timesheet->end_date)->format('d-m-Y') }}</td>
                                    @endif
                                <td>
                                    {{ $timesheet->employee->name }}
                                </td>
                                <td>{{ $timesheet->project->name }}</td>
                                <td>{{ Carbon\carbon::parse($timesheet->start_time)->format('h:i A') }}</td>
                                <td>{{ Carbon\carbon::parse($timesheet->end_time)->format('h:i A') }}</td>
                                <td>{{ $timesheet->supervisor->name }}</td>
                                <td>
                                    <a href="{{route('timesheet.edit',$timesheet->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                                    {{-- <form method="POST" action="{{route('member.destroy',$member->id)}}">
                                    @csrf
                                    @method('delete')
                                        <button class="btn btn-danger btn-sm warning" data-id={{$member->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                                    </form> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<script>
    $(document).ready(function () {
        $('#datatable').DataTable({
            order: [],
        });
    });
</script>

@endsection