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
    </div>
    <br>
    <div class="row">
        <div class="offset-2 col-8">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
