<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function index()
    {
         $roles = DB::table('roles')->join('user_roles','roles.id','=','user_roles.role_id')
             ->where('user_roles.user_id',Auth::user()->id)->pluck('roles.role');
        return view('home')->with('Roles',$roles);
    }

//    Data Table Create
    protected function getdata(){
        $users =  DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'roles.id', '=', 'user_roles.role_id')
            ->select('users.id','users.name','users.email','roles.role')
            ->where('users.id','!=',Auth::user()->id)
            ->get();
        return $users = Datatables::of($users)
            ->addColumn('action',function ($users){
              return '<button id="edit_btn" type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editUser" data-id="'.$users->id.'"><i class="ti-pencil"></i> Edit</button>
               <button type="button" id="delete_btn" data-id="'.$users->id.'" class="btn btn-sm btn-danger"><i class="ti-trash"></i> Delete</button>';

            })
            ->make(true);
    }

    protected  function  create(){
        $new_roles = DB::table('roles')->pluck('role','id');
        return view('home.create')->with('Roles',$new_roles);
    }

    protected function store(Request $data)
    {
        $this->validate($data,[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required',
        ]);

        $user = new User([
            'name' =>$data->get('name'),
             'email' => $data->get('email'),
            'password'=> bcrypt($data->get('password')),
         ]);
            $user->save();
             DB::table('user_roles')->insert([
                ['user_id' => $user->id ,'role_id'=>$data->get('roles')]
             ]);
        return redirect()->route('home.create')->with('success','User Created');
    }

    protected  function  getUser(Request $data){
        $id = $data->input('id');
        $user = User::find($id);
        $output = array(
            'id' => $user->id,
            'name'=> $user->name,
            'email'=> $user->email
        );
        echo json_encode($output);
    }

    protected  function  updateUser(Request $data)
    {
        $validate = Validator::make($data->all(),[
            'id'=>'required',
            'name'=>'required'
        ]);
        $error='';
        if($validate->fails()) {
            $error = $validate->errors()->all();
        }

        DB::table('users')
            ->where('id',$data->get('id'))
            ->update(['name'=>$data->get('name')]);
         $success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            User Updated
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
         $output = array(
             'error'=>$error,
          'success'=> $success
         );
         echo json_encode($output);
    }

    protected function deleteUser(Request $request){
        $id = $request->get('id');
        DB::table('users')->where('id', '=',$id)->delete();
        $success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                            User Deleted
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>';
        $output = array('success'=>$success);
        echo json_encode($output);
    }


    protected function getAttendanceForm() {
            $check_atd = $this->check_today_atd('attendance');
            $check_time = $this->check_today_atd('check_time');
        if( $check_atd == 'not marked'){
            $timeout = '';
            $timein = '';
            if ($check_time == 'new attendance')
            {
                $timeout ='disabled';
            }
            else if ($check_time != 'new attendance'){
                $timein = 'disabled';
            }
            $form = ' <form id="atd_form">
                          <div class="form-group">
                             <label for="timeIn">Time IN :</label>
                             <input id="timeIn" value="'.$check_time.'" name="timeIn" type="time" class="form-control" '.$timein.' required>
                         </div>
                            <div class="form-group">
                                <label for="timeOut">Time Out :</label>
                                <input id="timeOut" name="timeOut" value="" type="time" class="form-control"
                                '.$timeout.'
                                required>
                            </div>
                            <div class="form-group">
                                <label for="date">Date :</label>
                                <input id="date"  name="date" type="date" value="'.date("Y-m-d").'" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <input type="submit" class="btn btn-outline-purple">
                            </div>
                        </form> ';
        }
        else{
            $form = 'Your Attendance is marked . <i class="ti-face-smile"></i>';
        }

           return $form;
    }


    private function check_today_atd($check){
        date_default_timezone_set("Asia/Karachi");
      $atd = DB::table('attendances')->select('id','timein','timeout')
          ->where('user_id',Auth::user()->id)
          ->where('Date',date('Y-m-d'))
          ->get();

      if($check == 'attendance'){
           if(count($atd) > 0 && !empty($atd[0]->timein) && !empty($atd[0]->timeout) ){
               return 'marked';
           }
           else{
               return 'not marked';
           }
       }
      if($check == 'check_time'){
          if( empty($atd[0]->timein) && empty($atd[0]->timeout)){
              return 'new attendance';
          }
          else{
              return $atd[0]->timein;
          }
      }
       return '';
      }

    protected function mark_attendance(Request $data){
        if (empty($data->get('timeOut'))){
          DB::table('attendances')->insert(array(
            'timein' => $data->get('timeIn'),
            'timeout'=> '',
            'Date' => $data->get('date'),
              'user_id' =>Auth::user()->id
          ));
          echo json_encode('Your TimeIn is marked.');
        }
        else if (empty($data->get('timeIn'))){
          DB::table('attendances')->where('user_id',Auth::user()->id)
              ->where('Date',$data->get('date'))
              ->update(array(
           'timeout' => $data->get('timeOut')
          ));
          echo json_encode('Your Attendance is marked');
        }
        else{
            echo json_encode('Something went wrong');
        }
    }
}
