 @extends('layouts.master')
@section('title')
Dashboard
@endsection
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        <div class="col-6">
            <div class="text-left">
             <h2 class="title">Hi, {{\Illuminate\Support\Facades\Auth::user()->name }}</h2> <small>
                    @foreach ($Roles as $role)
                     <ul>
                         <li>{{$role}}</li>
                     </ul>
                    @endforeach
                </small>
            </div>
        </div>
        <div class="col-6">
            @if($Roles->contains('SuperAdmin'))
            <div class="text-right">
                <a href="{{route('home.create')}}" class="btn btn-primary">
                    <i class="ti-plus"></i> Create New User
                </a>
            </div>
            @endif
        </div>
    </div>
    <br>
    @if (!$Roles->contains('SuperAdmin'))
    <div class="row">
        <div class="col-12">
           <button class="btn btn-primary">Mark Attendance. <small>{{ $todayDate = date("d/M/y") }}</small></button>
        </div>
    </div>
    @endif
@endsection
