<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    private $perPage = 10;

    public function index(Request $request)
    {
        $roles = [];
        if($request->has('keyword')){
            $roles = Role::where('name','LIKE', "%{$request->keyword}%")->paginate($this->perPage);
        } else {
            $roles = Role::paginate($this->perPage);
        }
        return view('roles.index', [
            'roles' => $roles->appends(['keyword' => $request->keyword])
        ]);
    }

    public function select(Request $request){
        $roles = Role::select('id', 'name')->limit(7);
        if($request->has('q')){
            $roles->where('name', 'LIKE',"%{$request->q}%");
        }
        return response()->json($roles->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('roles.create',[
            'authorities' => config('permission.authorities')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
        [
            'name' => "required|string|max:50|unique:roles,name",
            'permissions' => "required"
            ],
            [],
            $this->attributes()
        );
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $request->name]);
            $role->givePermissionTo($request->permissions);
            Alert::success(
                trans('Create Role'),
                trans('Success'),
            );
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $role = Role::create(['name' => $request->name]);
            $role->givePermissionTo($request->permissions);
            Alert::error(
                trans('Create Role'),
                trans('Error',['error' => $th->getMessage()]),
            );
            return redirect()->back()->withInput($request->all());
            //throw $th;
        }finally{
            DB::commit();

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('roles.detail',[
            'role' => $role,
            'authorities' => config('permission.authorities'),
            'rolePermissions' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('roles.edit',[
            'role' => $role,
            'authorities' => config('permission.authorities'),
            'permissionChecked' => $role->permissions->pluck('name')->toArray()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $validator = Validator::make(
            $request->all(),
        [
            'name' => "required|string|max:50|unique:roles,name," . $role->id,
            'permissions' => "required"
            ],
            [],
            $this->attributes()
        );
        if($validator->fails()){
            return redirect()->back()->withInput($request->all())->withErrors($validator);
        }
        DB::beginTransaction();
        try {
            $role->name = $request->name;
            $role->syncPermissions($request->permissions);
            $role->save();
            Alert::success(
                trans('Edit Role'),
                trans('Success')
            );
            return redirect()->route('roles.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            $role = Role::create(['name' => $request->name]);
            $role->givePermissionTo($request->permissions);
            Alert::error(
                trans('Edit Role'),
                trans('Error',['error' => $th->getMessage()])
            );
            return redirect()->back()->withInput($request->all());
            //throw $th;
        }finally{
            DB::commit();

        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        DB::beginTransaction();
        try {
            $role->revokePermissionTo($role->permissions->pluck('name')->toArray());
            $role->delete();
            Alert::success(
                trans('Delete Role'),
                trans('Success'),
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            $role = Role::create(['name' => $request->name]);
            $role->givePermissionTo($request->permissions);
            Alert::error(
                trans('Delete Role'),
                trans('Error',['error' => $th->getMessage()]),
            );
        }finally{
            DB::commit();

        }
        return redirect()->route('roles.index');
    }

    private function attributes()
    {
        return [
            'name' => trans('Input name'),
            'permissions' => trans('Input permission'),
        ];
    }
}
