<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleCreateRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Models\Role;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('auth.role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(RoleCreateRequest $request)
    {
        $data = new Role();
        $data->name = $request->name;
        $data->slug = strtolower($request->name);

        /**
         *  Permission Here
         */
        $permissions = '';//collect(json_decode($this->permissions($request)))->toArray();
        $data->permissions = $permissions;

        $data->save();

        Flash::success(__('auth.role_creation_successful'));
        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $role = Role::find($id);
        if (empty($role)) {
            Flash::error( __('global.denied'));
            return redirect()->back();
        }
        return view('auth.role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param updateRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(RoleUpdateRequest $request, $id)
    {
 
        $dataDb = Role::find($id);
        
        if (empty($dataDb)) {
            Flash::error( __('global.denied'));

            return redirect()->back();
        }

        $dataDb->name = $request->name;
        $dataDb->slug = strtolower($request->name);
        $dataDb->save();

        Flash::success( __('auth.role_update_successful'));

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $userDb = Sentinel::getUser();
        $dataDb = Sentinel::findRoleById($id);

        if (empty($dataDb)) {
            Flash::error(__('global.not_found'));

            return redirect()->route('role.index');
        }

        $dataDb->users()
            ->detach($userDb);
        $dataDb->delete();

        Flash::success(__('auth.role_delete_successful'));

        return redirect()->route('role.index');

    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function datatable()
    {
        $dataDb = Role::select([
            'id',
            'slug',
            'name',
            'created_at',
            'updated_at',
        ])->whereNotIn('slug',['admin','superadmin']);
        return DataTables::eloquent($dataDb)
            ->addColumn('action', function ($dataDb) {
                return '<a href="' . route('role.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                <a href="#" data-message="' . trans('auth.role_delete_confirmation', ['name' => $dataDb->name]) . '" data-href="' . route('role.destroy', $dataDb->id) . '" id="tooltip" data-method="DELETE" data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
            })
            ->make(true);
    }
    
    /**
     * For Add Permission
     *
     * @param $request
     *
     * @return string
     */
    private function permissions($request)
    {

        //Dashboard
        $permissions['dashboard'] = true;

        $request = $request->except(array('_token', 'name', '_method', 'previousUrl'));
  
        foreach ($request as $key => $value) {
            $permissions[preg_replace('/_([^_]*)$/', '.\1', $key)] = true;
        }
        
        return json_encode($permissions);
    }

    /**
     * Duplicate Form
     *
     * @param $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function duplicate($id)
    {
        $data = Role::where('id', $id)->firstOrFail();

        $permission = json_decode(json_encode($data->permissions), true);

        return view('auth.role.duplicate', ['data' => $data, 'permissions' => $permission]);
    }
}
