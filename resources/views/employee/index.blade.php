@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Employees List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Employees List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="card-header py-3">
    <a href="{{ route('employee.create') }}" class="btn btn-primary btn-sm"> <i class="fas fa-plus"> </i> Add Employee</a>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive w-100">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">ID</th>
                                <th class="align-middle">Name</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->sup_id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>
                                    @if ($employee->active == 1)
                                        <span class="badge rounded-pill bg-success">Active</span>
                                    @elseif (($employee->active == 0))
                                    <span class="badge rounded-pill bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('employee.edit',$employee->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
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

@endsection