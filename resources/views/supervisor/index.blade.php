@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Supervisors List</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboards</a></li>
                    <li class="breadcrumb-item active">Supervisors List</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="card-header py-3">
    <a href="{{ route('supervisor.create') }}" class="btn btn-primary btn-sm"> <i class="fas fa-plus"> </i> Add Supervisor</a>
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
                                <th class="align-middle">Email</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">48Hr Limit</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->sup_id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->active == 1)
                                        <span class="badge rounded-pill bg-success">Active</span>
                                    @elseif (($user->active == 0))
                                    <span class="badge rounded-pill bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->entry_limit == 1)
                                        <a href="{{ route('disable', $user->id) }}" class="btn btn-success btn-sm"> <b>Enabled</b></a>
                                    @elseif ($user->entry_limit == 0)
                                        <a href="{{ route('enable', $user->id) }}" class="btn btn-danger btn-sm"> <b>Disabled</b></a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('supervisor.edit',$user->id)}}" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
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