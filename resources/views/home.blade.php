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
            <div class="updateSuccess"></div>
            <table id="users_table" class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Edit user Modal -->
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="Edit User" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body jumbotron">
                    <div class="form_output"></div>
                    <form id="edit_form">
                        {{ csrf_field() }}
                        <input id="id" name="id" type="hidden" value="" >
                        <div class="form-group">
                            <label for="name" class="col-6 control-label">Name</label>

                            <div class="col-6">
                                <input id="name" type="text" class="form-control" name="name" value="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-6">
                                <input id="email" type="text" class="form-control disabled" name="email" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
 @push('script')
     <script>
         $(document).ready(function () {
             $('#users_table').DataTable({
                 processing: true,
                 serverSide: true,
                 method:'post',
                 ajax: '{!! route('home.getdata') !!}',
                 columns: [
                     { data: 'id', name: 'id'},
                     { data: 'name', name: 'name' },
                     { data: 'email', name: 'email' },
                     { data: 'role', name: 'role' },
                     { data:'action',name:'action',orderable:false,searchable:false }
                 ]
             });
             $(document).on('click','#edit_btn',function(){
                var id = $(this).attr('data-id');
                $.ajax({
                    url:'{{route("home.getUser")}}' ,
                    method: 'get',
                    data:{id:id},
                    dataType:'json',
                    success:function (data) {
                        $('#name').val(data.name);
                        $('#id').val(data.id);
                        $('#email').val(data.email);
                    }
                });
             });

             $('#edit_form').on('submit',function (event) {
                event.preventDefault();
                var form_data = $(this).serialize();
                $.ajax({
                   url:'{{route('home.updateUser')}}',
                    method:'post',
                    data:form_data,
                    dataType: 'json',
                    success:function (data) {
                        if (data.error.length > 0 ){
                            var edit_error = '';
                            for (var i = 0 ; i < data.error.length;i++){
                                edit_error +='<div class="alert alert-danger">'+data.error[i]+'</div>';
                            }
                            $('#form_output').html(edit_error);
                        }
                        else{
                            $('.updateSuccess').html(data.success);
                            $('#edit_form')[0].reset();
                            $('#editUser').modal('hide');
                            $('#users_table').DataTable().ajax.reload();
                        }
                    }
                });
             });
             $(document).on('click','#delete_btn',function () {
                if (confirm('Are you sure you want to delete this user ?')){
                    var id = $(this).attr('data-id');
                    $.ajax({
                        url:'{{route('home.deleteUser')}}',
                        method:'get',
                        data:{id:id},
                        success:function (data) {
                            $('#updateSuccess').html(data.success);
                            $('#users_table').DataTable().ajax.reload();
                        }
                    });
                }
             });
         });
     </script>
 @endpush
