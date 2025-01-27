<?php
namespace App\Http\Controllers\Auth;
use App\Events\Notification;
use App\Exceptions\EmailFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\StaffCreateRequest;
use App\Http\Requests\StaffUpdateRequest;
use App\Models\Role;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
use App\Mail\ResetPassword;
use App\Mail\UserRegistration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Config;
use App\Models\Checkout;
use Illuminate\Validation\Rule;
class StaffController extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.staff.index');
    }

    public function create()
    {   
        return view('auth.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StaffCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(StaffCreateRequest $request)
    {
        $email = $request->email;
        $user  = Sentinel::getUser()->first_name;
        $config = Config::where('type', 'staff')->get()->first();
        //DB::beginTransaction();
        try {
            $data = [
                'member_id'  => $request->member_id,
                'first_name' => $request->first_name,
                // 'last_name'  => $request->last_name,
                'email'      => strtolower($email),
                'group'   => $request->group,
                'designation'   => $request->designation,
                'role'   => $request->role,
                'created_by' => $user,
                'updated_by' => $user,
                'is_active' => 1
            ];
           
            //Create a new user
            $user = User::Create($data);

            #Get Activation Code
            $activationCreate = Activation::create($user);

            #Activate this account
            Activation::complete($user, $activationCreate->code);

            //Attach the user to the role
            $role = Sentinel::findRoleById($request->role);
            $role->users()
                ->attach($user);
            Flash::success( __('auth.account_creation_successful'));
            return redirect()->route('staff.index');

        } catch (\Exception $exception) {
            dd($exception);
            return redirect()->route('staff.index');
        }
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
        $user = Sentinel::findUserById($id);

        // dd($user);
        if (empty($user)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('staff.index');
        }

        $roleDb = Role::whereNotIn('slug', ['admin','superadmin'])->pluck('name','id');

        $userRole = $user->roles[0]->id ?? null;
        return view('auth.staff.view', compact('user','roleDb','userRole'));
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
        $user = Sentinel::findUserById($id);

        // dd($user);
        if (empty($user)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('staff.index');
        }

        $roleDb = Role::whereNotIn('slug', ['admin','superadmin'])->pluck('name','id');

        $userRole = $user->roles[0]->id ?? null;
        return view('auth.staff.edit', compact('user','roleDb','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StaffUpdateRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'member_id'  => ['required', 'regex:/^[A-Za-z][0-9]{5}$/', Rule::unique('users')->ignore($id)],
            'email'      => ['required', 'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', Rule::unique('users')->ignore($id)],
            'first_name' => 'required|regex:/(^[A-Za-z ]+$)+/',
            // 'last_name'  => 'required|regex:/(^[A-Za-z0-9_-_ ]+$)+/',
            'role'       => 'required',
            'designation'       => 'required',
            'group'       => 'required',
        ], [
            'member_id.regex' => 'Staff No must be 1 alphabet followed by 5 numbers',
            'first_name.regex' => 'Name should only contain alphabets and spaces.',
        ]);
    
        $user = Sentinel::findById($id);

        if (empty($user)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('staff.index');
        }
        if($user->member_id != $request->member_id){
            $hasTakenCheckouts = Checkout::where('checkout_by', $id)->where('status', 'taken')->first();
            if ($hasTakenCheckouts) {
                Flash::error( __('auth.checkouts'));

                return redirect()->route('staff.index');
            } 
        }
       // DB::beginTransaction();
        try {

            $oldRole = Sentinel::findRoleById($user->roles[0]->id ?? null);

            $credentials = [
                'first_name' => $request->first_name,
                // 'last_name'  => $request->last_name,
                'email'      => $request->email,
                'group'   => $request->group,
                'designation'   => $request->designation,
                'role'   => 7,
                'member_id'  => $request->member_id,
            ];


            #Valid User For Update
            $role = Sentinel::findRoleById($request->role ?? 7);

            if ($oldRole) {
                #Remove a user from a role.
                $oldRole->users()
                    ->detach($user);
            }

            #Assign a user to a role.
            $role->users()
                ->attach($user);

            #Update User
            Sentinel::update($user, $credentials);

            $user->member_id = $request->member_id;
           
            $user->designation = $request->designation;
            $user->group = $request->group;
            $user->save();

            Flash::success( __('auth.update_successful'));
           // DB::commit();
            return redirect()->route('staff.index');

        } catch (\Exception $exception) {
            Log::error('Reset password email  email sent failure.');
            return redirect()->route('staff.index');    
        }
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

        $data = User::find($id);

        if (empty($data)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('staff.index');
        }
        if($user->member_id != $request->member_id){
            $hasTakenCheckouts = Checkout::where('checkout_by', $id)->where('status', 'taken')->first();
            if ($hasTakenCheckouts) {
                Flash::error( __('auth.checkouts'));

                return redirect()->route('staff.index');
            } 
        }
        $data->delete();

       Flash::success( __('auth.delete_account'));

        return redirect()->route('staff.index');

    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function datatable(Request $request){
       try {
        if ($request->ajax()) {
            $dataDb = User::select([
                    'users.id',
                    'member_id',
                    'first_name',
                    'last_name',
                    'email',
                    'group',
                    'designation',
                    'users.created_at',
                    'users.updated_at',
                    'is_active'
                ])
                ->with('roles')
                ->whereHas('roles', function ($q) {
                    $q->whereIn('slug', ['staff']);
                });

            return DataTables::eloquent($dataDb)
                ->addColumn('action', function ($dataDb) {
                    return '<a href="' . route('staff.show', $dataDb->id) . '" id="tooltip" title="View"><span class="label label-primary label-sm"><i class="fa fa-eye"></i></span></a>
                    <a href="' . route('staff.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                            <a href="#" data-message="' . trans('auth.delete_confirmation', ['name' => $dataDb->email]) . '" data-href="' . route('staff.destroy', $dataDb->id) . '" id="tooltip" data-method="DELETE" data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
                })
                ->addColumn('role', function ($dataDb) {
                    if ($dataDb->roles->isNotEmpty()) {
                        return implode(', ', collect($dataDb->roles)->pluck('name')->all());
                    }
                })
                ->addColumn('status', function ($dataDb) {
                        if ($dataDb->is_active == 1) {
                            return '<a href="#" data-message="' . trans('auth.deactivate_subheading', ['name' => $dataDb->email]) . '" data-href="' . route('user.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('auth.deactivate_this_user') . '" data-title-modal="' . trans('auth.deactivate_heading') . '" data-toggle="modal" data-target="#delete" title="' . trans('auth.deactivate_this_user') . '"><span class="label label-success label-sm">' . trans('auth.index_active_link') . '</span></a>';
                        }
                    
                    return '<a href="#" data-message="' . trans('auth.activate_subheading', ['name' => $dataDb->email]) . '" data-href="' . route('user.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('auth.activate_this_user') . '" data-title-modal="' . trans('auth.deactivate_heading') . '" data-toggle="modal" data-target="#delete" title="' . trans('auth.activate_this_user') . '"><span class="label label-danger label-sm">' . trans('auth.index_inactive_link') . '</span></a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    } catch (\Exception $e) {
        // Return the error message in JSON format
        return response()->json(['error' => 'Server error: ' . $e->getMessage()]);
    }
}

    /**
     * For Active or Deactive User
     *
     * @param $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function status($id)
    {

        $user = Sentinel::findById($id);
      
    
        $activation = Activation::completed($user);

        #Remove activation code
        Activation::remove($user);

        if ($activation !== false) {
            #Deactivated This Activation
            $user->is_active=0;
            $user->save();
            if ($user->id === Sentinel::getUser()->id) {
               Flash::error( __('auth.deactivate_current_user_unsuccessful'));

                return redirect()->route('staff.index');
            }

           Flash::success( __('auth.deactivate_successful'));

            return redirect()->back();
        }

        #Own User Cannot Change The User Status
        if ($user->id === Sentinel::getUser()->id) {
            $user->is_active=1;
            $user->save();
           Flash::error( __('auth.active_current_user_unsuccessful'));

            return redirect()->back();
        }

        #Get Activation Code
        $activationCreate = Activation::create($user);

        #Activate this account
        Activation::complete($user, $activationCreate->code);
        $user->is_active=1;
        $user->save();

       Flash::success( __('auth.activate_successful'));

        return redirect()->back();
    }

}
