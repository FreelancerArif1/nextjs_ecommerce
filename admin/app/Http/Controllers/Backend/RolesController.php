<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class RolesController extends Controller
{
    public $user;

    public function __construct(){

        $this->middleware(function($request,$next){
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(is_null($this->user) || !$this->user->can('role.view')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $roles = Role::all();
        return view('backend.pages.roles.list',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(is_null($this->user) || !$this->user->can('role.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $permissions = Permission::all();
        return view('backend.pages.roles.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(is_null($this->user) || !$this->user->can('role.create')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        //Data Validation
        $request->validate([
            'name' => 'required | max:100 | unique:roles'
            ],
            [
                'name.unique' => 'Role name has already been taken!',
                'name.required' => 'Role name is required!',
            ]
        );

        $role = Role::create([ 'name' => $request->name, 'guard_name' => 'web' ]);
        $permissions = $request->permissions;
        if($permissions){
            $role->syncPermissions($permissions);
        }
        return redirect()->route('roles.index')->with('success', 'Role successfully created!');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(is_null($this->user) || !$this->user->can('role.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $role = Role::findById($id);
        $permissions = Permission::all();
        return view('backend.pages.roles.edit',compact('permissions','role')); 
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
        if(is_null($this->user) || !$this->user->can('role.edit')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        //Data Validation
        $request->validate([
            'name' => 'required | max:100 | unique:roles,name,' .$id
            ],
            [
                'name.unique' => 'Role name has already been taken!',
                'name.required' => 'Role name is required!',
            ]
        );

        $role = Role::findById($id);
        $role->name = $request->name;
        $role->save();
        $permissions = $request->permissions;
        if($permissions){
            $role->syncPermissions($permissions);
        }
        return redirect()->route('roles.index')->with('success', 'Role successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(is_null($this->user) || !$this->user->can('role.delete')){
            return redirect()->route('admin.index')->with('failed', 'You don\'t have enough privileges to perform this action!');
        }

        $role = Role::findById($id);
        if($role){
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'Role successfully deleted!');
        }else{
            return redirect()->route('roles.index')->with('error', 'Role can be deleted!');
        }
    }
}
