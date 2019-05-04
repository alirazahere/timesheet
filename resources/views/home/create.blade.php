@extends('layouts.master')
@section('title')
    Create User
@endsection
@section('content')
    <div class="row">
        <div class="offset-2 col-7">
            <div class="panel jumbotron panel-primary ">
                <div class="panel-heading"><h3 class="title">Create New User</h3></div>
                <br>
                <div class="panel-body">

                    @if (Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{Session::get('success')}}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <ul class="text-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    <form method="post" action="{{ route('home.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-6 control-label">Name</label>

                            <div class="col-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-6">
                                <input id="email" type="email" class="form-control" name="email" value="" required>

                                @if ($errors->has('email'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-6 control-label">Password</label>

                            <div class="col-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password text-danger'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-6 control-label">Confirm Password</label>

                            <div class="col-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="roles" class="col-6 control-label">Roles</label>

                            <div class="col-md-6">
                                <select id="roles" name="roles" class="form-control">
                                    @foreach($Roles as $key => $role)
                                        <option value="{{$key}}">{{$role}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
