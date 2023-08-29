@extends('layouts.master')
@section('content')

    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">User Role Management</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Role User</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                    {{--  <a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_user"><i class="fa fa-plus"></i> Add User</a>--}}
                    </div>
                </div>
            </div>
            <!-- /Page Header -->

            <!-- Search Filter -->
            <form action="{{ route('roleUser') }}" method="get">
                @csrf
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3">

                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <select class="form-control select" id="user" name="user">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                <option value="{{$user->id}}" @if( (request()->get('user'))==$user->id ) selected @endif >{{$user->name}} ({{$user->type}})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <button type="sumit" class="btn btn-success btn-block"> Search </button>
                    </div>
                    <div class="col-sm-6 col-md-3">

                    </div>

                </div>
            </form>
            <!-- /Search Filter -->

            {{-- message --}}
            {!! Toastr::message() !!}
            <form action="{{route('user/role/update')}}" method="post">
                @csrf
          <!-- module permission -->
            <div class="row">
                <div class="col-md-12">
                    <h4 class="">Module Permission List</h4>
                    <div class="table-responsive">

                        <table class="table custom-table table-borderless table-hover" id="DataTable">
                            <thead class="thead-light">
                            <tr>
                                {{--  <th>#</th>--}}
                                <th>Module</th>
                                <th>Module Permission</th>
                                <th>Deny</th>
                                <th>Allow</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $moduleNameArr=[]; @endphp
                            @foreach ($permissionModule as $key=>$module )
                                <tr>
                                    {{--  <td>{{$key+1}}</td>--}}
                                    <td style="font-weight: bold">
                                        @php $count= in_array($module->nameInfo->name,$moduleNameArr);
                                                            if($count<1){
                                                                array_push($moduleNameArr,$module->nameInfo->name);
                                                                echo $module->nameInfo->name;
                                                            }
                                        @endphp
                                    </td>
                                    <td>{{$module->name}}</td>

                                    @if(empty($module->userPermissions))
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="userPermission[{{$module->id}}]" id="userPermission[{{$module->id}}]" value="deny" checked>
                                                <label class="form-check-label" for="deny">
                                                    deny
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="userPermission[{{$module->id}}]" id="userPermission[{{$module->id}}]" value="write">
                                                <label class="form-check-label" for="deny">
                                                    allow
                                                </label>
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="userPermission[{{$module->id}}]" id="userPermission[{{$module->id}}]" value="deny" >
                                                <label class="form-check-label" for="write">
                                                    deny
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="userPermission[{{$module->id}}]" id="userPermission[{{$module->id}}]" value="write" checked>
                                                <label class="form-check-label" for="write">
                                                    allow
                                                </label>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <input type="text" value="{{request()->get('user')}}" name="user_id" hidden />
                        <div class="submit-section float-right">
                            <button class="btn btn-info submit-btn">Update Now</button>
                        </div>
                    </div>
                </div>
            </div>
          <!-- / module permission -->
            </form>
        </div>
        <!-- /Page Content -->
    </div>

@endsection

@section('script')
@endsection
