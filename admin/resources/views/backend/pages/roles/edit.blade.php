@extends('backend.layouts.master')
@section('title','Role Update - '.config('concave.cnf_appname'))
@section('content')
<style>
  .form-check.form-check-flat {
    display: inline-block;
    margin-right: 30px;
}
.permissions_head{
  background: #43b1f017;
    padding: 8px 0px 0px;
}
</style>



<div class="grid-margin stretch-card">
	<div class="card">
		  <div class="card-body">
      <span class="card-title">Dashboard > User Roles > Edit Role</span>
      <a class="btn btn-success float-right" href="{{ route('roles.index')}}">View Roles List</a>
		  </div>
	</div>
</div>
			<div class="row">
              <div class="col-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <form class="form-sample" method="post" action="{{ route('roles.update',$role->id)}}" >
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                          <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Role Name</label>
                            <div class="col-sm-9">
                              <input type="text" name="name" class="form-control" value="{{$role->name}}" placeholder="Role Name">
                            </div>
                          </div>
                        </div>
                    </div>

                        <div class="row permissions_head">
                          <div class="col-md-2"><h4>Permissions</h4></div>
                          <div class="col-sm-10">
                              <div class="form-check form-check-flat">
                                <label class="form-check-label" style="text-transform: capitalize;">
                                  <input type="checkbox" {{ App\Models\Admins::roleHasPermission($role,$permissions) ? 'checked' : '' }} id="select_all" class="form-check-input">Select All<i class="input-helper"></i>
                                </label>
                              </div>
                          </div>
                        </div><hr>
  
                        <?php
                        $permissionsArray = [];
                        foreach ($permissions as $permission){
                          $sectionTitle = explode('.',$permission->name);
                          $permissionsArray[$sectionTitle[0]][] =  $permission->name;
                        }
                        ?>


                        @foreach ($permissionsArray as $key=>$permissionData)

                        <div class="row">
                            <p class="col-md-2" style="text-transform: capitalize;">{{$key}} Group</p>
                            <div class="col-sm-10">
                                @foreach($permissionData as $permission)
                                    <div class="form-check form-check-flat">
                                      <label class="form-check-label" style="text-transform: capitalize;">
                                        <input name="permissions[]" type="checkbox" {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}  class="form-check-input" value="{{$permission}}">{{ str_replace('.',' ',$permission) }}<i class="input-helper"></i>
                                      </label>
                                    </div>
                                @endforeach
                            </div>
                          </div><hr>
                        @endforeach


                        <div class="col-md-12">
                          <div class="form-group">
                            <p class="text-right">
                                <button class="btn btn-primary" name="save" type="submit">Update Role</button>
                            </p>
                          </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
      </div>

@endsection