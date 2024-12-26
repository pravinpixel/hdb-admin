<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::whereNotIn('slug',['admin','superadmin'])->pluck('name', 'id');
        return view('auth.permission.index', compact('roles'));
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getPermission(Request $request)
    {
        $routes = [];
        $role_id = $request->input('id');
        $role = Sentinel::findRoleById($role_id);
        $permissions = $role->permissions;
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection as $value) {
            $explode = explode('.', $value->getName());
            if(count($explode) > 0) {
                $key = array_slice($explode,-2,1)[0];
                if(isset($key) && $key != '') {
                    $routes[$key][] = $value->getName();
                }
                unset($routes['ignition']);
                unset($routes['login']);
                unset($routes['logout']);
            }
        }
        return view('auth.permission.permission-view', compact('routes','permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selectedPremissions = collect($request->all())->keys()->toArray();
        $role_id = $request->input('selected_role_id');
        $routeCollection = Route::getRoutes();
        $result = [];
        foreach ($routeCollection as $value) { 
            $route_name = $value->getName();
            $explode = explode('.', $route_name );
            if(count($explode) > 0) {
                if(in_array(str_replace('.','_', $route_name ), $selectedPremissions)) {
                    $result[ $route_name ] = true;
                } else {
                    $result[ $route_name ] = false;
                }
            }
        }
        $this->updatePermission($role_id, $result);
        $new_request = new Request();
        $new_request['id'] = $role_id;
        return $this->getPermission($new_request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePermission($id , $permissions)
    {
        $role = Sentinel::findRoleById($id);
        $role->permissions = $permissions;
        return $role->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
