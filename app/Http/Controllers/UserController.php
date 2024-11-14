<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:user_show', ['only' => 'index']);
    //     $this->middleware('permission:user_create', ['only' => ['create','store']]);
    //     $this->middleware('permission:user_update', ['only' => ['edit','update']]);
    //     $this->middleware('permission:user_detail', ['only' => 'show']);
    //     $this->middleware('permission:user_delete', ['only' => 'destroy']);
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index',[
            'users' => User::all(),
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
        "name" => "required|string|max:30",
        "role" => "required",
        "email" => "required|email|unique:users,email",
        "password" => "required|min:6|confirmed"
        ],
        [],
        $this->attributes()
    );
    if($validator->fails()) {
        $request['role'] = Role::select('id','name')->find($request->role);
        return redirect()
        ->back()
        ->withInput($request->all())
        ->withErrors($validator);
    }
    DB::beginTransaction();
    try {
        $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password)
        ]);
        $user->assignRole($request->role);
        Alert::success(
            trans('create user'),
            trans('success')
        );
        return redirect()->route('users.index');
    } catch (\Throwable $th) {
        DB::rollBack();
        Alert::error(
            trans('create user'),
            trans('error',['error' => $th->getMessage()]),
        );
        $request['role'] = Role::select('id', 'name')->find($request->role);
        return redirect()
        ->back()
        ->withInput($request->all())
        ->withErrors($validator);
    } finally {
        DB::commit();
    }
    
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'roleSelected' => $user->roles->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        dd("INI REQUEST", $request, "INI USER", $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }

    private function attributes()
    {
        return [
        "name" => trans('User input name'),
        "role" => trans('User input role'),
        "email" => trans('User input email'),
        "password" => trans('User input password')
        ];
    }
}
