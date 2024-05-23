<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Debtor;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;


class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    // $data = User::where('role', `!=`, 2)->orderBy('id', 'DESC')->paginate(5);
    $data = User::whereNotNull('status')->orderBy('id', 'DESC')->paginate(5);

    // return $data;
    return view('users.index', compact('data'))
      ->with('i', ($request->input('page', 1) - 1) * 5);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $roles = Role::where('name','!=','Admin')->pluck('name', 'name')->all();
    return view('users.create', compact('roles'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // return $request;
    $this->validate($request, [
      'name' => 'required',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|same:confirm-password',
      'roles_name' => 'required'
    ],[
      "name.required" => "الاسم مطلوب",
      'email.required' =>"الايميل مطلوب",
      'email.unique' =>'الايميل مكرر',
      'roles_name'=>'لاصلاحيات مطلوية',

    ]);

    $input = $request->all();
    $input['password'] = Hash::make($input['password']);

    $user = User::create($input);
    $user->assignRole($request->input('roles'));

    return redirect()->route('users.index')
      ->with('success', 'تم انشاء المستخدم بنجاح');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $user = User::find($id);
    return view('users.show', compact('user'));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $user = User::find($id);
    $roles = Role::where('name','!=','Admin')->pluck('name', 'name')->all();

    $userRole = $user->roles->pluck('name', 'name')->all();

    return view('users.edit', compact('user', 'roles', 'userRole'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required',
      'email' => 'required|email|unique:users,email,'.$id,
      'password' => 'required|same:confirm-password',
      'roles_name' => 'required'
    ],[
      "name.required" => "الاسم مطلوب",
      'email.required' =>"الايميل مطلوب",
      'email.unique' =>'الايميل مكرر',
      'roles_name'=>'لاصلاحيات مطلوية',

    ]);

    $input = $request->all();
    if (!empty($input['password'])) {
      $input['password'] = Hash::make($input['password']);
    } else {
      $input = Arr::except($input, array('password'));
    }

    $user = User::find($id);
    $user->update($input);
    DB::table('model_has_roles')->where('model_id', $id)->delete();

    $user->assignRole($request->input('roles_name'));

    return redirect()->route('users.index')
      ->with('success', 'تم التعديل بنجاح');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    User::find($id)->delete();
    Debtor::where('user_id',$id)->delete();
    return redirect()->route('users.index')
      ->with('success', 'User deleted successfully');
  }
}
