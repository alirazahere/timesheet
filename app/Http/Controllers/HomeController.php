<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;

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
}
