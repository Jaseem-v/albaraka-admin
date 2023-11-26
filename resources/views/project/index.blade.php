@extends('layouts.master')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Projects List</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Projects List</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="card-header py-3">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#myModal"> <i class="fas fa-plus"> </i> Add Project</button>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                    <thead>
                    <tr>
                        <th>S.I</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>


                    <tbody>

                    @foreach ($projects as $project)
                    <tr>
                        <td>{{ $loop->index +1 }}</td>
                        <td>{{ $project->name }}</td>
                        <td>
                            @if ($project->status == 1)
                                <span class="badge rounded-pill bg-success">Active</span>
                            @elseif($project->status == 0)
                                <span class="badge rounded-pill bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm float-left mr-1" style="height:30px; width:30px;border-radius:50%" data-bs-toggle="modal" data-bs-target="#myModal{{ $project->id }}"><i class="fas fa-edit"></i></a>
                            {{-- <form method="POST" action="{{route('witnesstype.destroy',$type->id)}}">
                            @csrf
                            @method('delete')
                                <button class="btn btn-danger btn-sm warning" data-id={{$type->id}} style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                            </form> --}}
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div id="myModal{{ $project->id }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <form method="POST" action="{{ route('project.update',$project->id) }}">
                        @csrf
                        @method('PATCH')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Edit Project</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row"> 
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-email-input" class="form-label">Name</label>
                                                <input type="text" name="name" value="{{ $project->name }}" class="form-control" id="formrow-password-input">
                                                @error('name')
                                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-email-input" class="form-label">Select Status</label>
                                                <select name="status"class="form-control">
                                                    <option value="1" {{ old('status',$project->status == 1 ? 'selected' : '') }}>Active</option>
                                                    <option value="0" {{ old('status',$project->status == 0 ? 'selected' : '') }}>Inactive</option>
                                                </select>
                                                @error('status')
                                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>

                    @endforeach

                    <!-- Modal -->
                    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <form method="POST" action="{{ route('project.store') }}">
                        @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0" id="myModalLabel">Add Project</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row"> 
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-email-input" class="form-label">Name</label>
                                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="formrow-password-input">
                                                @error('name')
                                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="formrow-email-input" class="form-label">Select Status</label>
                                                <select name="status"class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                @error('status')
                                                    <span class="badge badge-soft-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                   
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->

@endsection