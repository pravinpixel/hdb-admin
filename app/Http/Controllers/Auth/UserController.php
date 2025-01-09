<?php
namespace App\Http\Controllers\Auth;
use App\Events\Notification;
use App\Exceptions\EmailFailureException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.user.index');
    }

    public function create()
    {   
        $config = Config::where('type', 'staff')->get()->first();
        $member_id = $config->item_prefix.''.$config->item_number;
        $roleDb = Role::whereNotIn('slug',['staff'])->pluck('name','id');
        $userRole = null;
        return view('auth.user.create', compact('roleDb','userRole','member_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserCreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     * @throws \Exception
     * @throws \Throwable
     */
    public function store(UserCreateRequest $request)
    {
        $email = $request->email;
        $user  = Sentinel::getUser()->first_name;
        $config = Config::where('type', 'staff')->get()->first();
        //DB::beginTransaction();
        try {
            $data = [
                'member_id'  => $config->item_prefix.''.$config->item_number,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => strtolower($email),
                'password'   => Hash::make($request->password),
                'mobile'     => $request->mobile,
                'address'    => $request->address,
                'created_by' => $user,
                'updated_by' => $user,
                'is_active'  => 1
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

        //    // DB::commit();
        //     $details = [
        //         'title'    => 'User Registration',
        //         'user'     => $user,
        //         'password' => $request->password,
        //         'role'     => $role,
        //         'url'      => route('login')
        //     ];

            Flash::success( __('auth.account_creation_successful'));

            // Mail::to($request->email)->send(new UserRegistration( $details));
            // if(count(Mail::failures()) > 0){
            //     event(new Notification("User registration email sent failure [to - {$user->email}]", "user_registration_failure", $user->id,null,null,null,0));
            //     Log::error('User registration email sent failure.');
            // }else {
            //     event(new Notification("User registration email sent successfully [to - {$user->email}]", "user_registration_success", $user->id,null,null,null,0));
            //     Log::info('User registration email sent successfully');
            // }
           
            $this->updateConfigStaffNumber();
            return redirect()->route('user.index');

        } catch (\Exception $exception) {

            // event(new Notification("User registration email sent failure [to - {$user->email}]", "user_registration_failure", $user->id));
            // Log::error('User registration email sent failure.');
            return redirect()->route('user.index');
            // DB::rollBack();

            // Flash::error( $exception->getMessage() . ' ' . $exception->getLine());

            // return redirect()
            //     ->back()
            //     ->withInput($request->all());
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
        //
    }
    public function updateConfigStaffNumber()
    {
        $config = Config::where('type', 'staff')->get()->first();
        $config->item_number =  $config->item_number + 1;
        Log::info('Increment Staff number');
        return $config->save();
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

        if (empty($user)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('user.index');
        }

        $roleDb = Role::whereNotIn('slug',['staff'])->pluck('name','id');

        $userRole = $user->roles[0]->id ?? null;

        $member_id = $user->member_id;

        return view('auth.user.edit', compact('user','roleDb','userRole','member_id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserUpdateRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(UserUpdateRequest $request, $id)
    {

        $user = Sentinel::findById($id);

        if (empty($user)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('user.index');
        }

       // DB::beginTransaction();
        try {

            $oldRole = Sentinel::findRoleById($user->roles[0]->id ?? null);

            $credentials = [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'address'    => $request->address,
                'email'      => $request->email,
                'mobile'     => $request->mobile,
                'member_id'  => $request->member_id,
            ];

            #If User Input Password
            if ($request->password) {
                
                $validator = Validator::make($request->all(), [
                    'password' => 'min:5',
                ]);

                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $details = [
                    'title'    => 'Reset Password',
                    'password' => $request->password,
                    'url' => route('user.index'),
                    'user' => $user
                ];
               
                // $credentials['password'] = Hash::make($request->password);
                $credentials['password'] = $request->password;
            }

            #Valid User For Update
            $role = Sentinel::findRoleById($request->role);

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

            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->save();

            Flash::success( __('auth.update_successful'));
           // DB::commit();
        if ($request->password) { 
            // Mail::to($user->email)->send(new ResetPassword($details));
            // if(count(Mail::failures()) > 0){
            //     event(new Notification("Reset password email sent failure [to - {$user->email}]", "update_user_password_failure", $user->id,null,null,null,0));
            //     Log::error('Reset password email  email sent failure.');
            // }else {
            //     event(new Notification("Reset password email sent successfully [to - {$user->email}]", "update_user_password_success", $user->id));
            //     Log::info('Reset password email sent successfully');
            // }
        }
    
            return redirect()->route('user.index');

        } catch (\Exception $exception) {

         // DB::rollBack();
    
           //Flash::error( $exception->getMessage() . ' ' . $exception->getLine());

            //event(new Notification("Reset password email sent failure [to - {$user->email}]", "user_update_password_failure", $user->id,null,null,null,0));
            Log::error('Reset password email  email sent failure.');
            return redirect()->route('user.index');
            // return redirect()
            //     ->back()
            //     ->withInput($request->all());
            
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

        $data = Sentinel::findById($id);

        if (empty($data)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('user.index');
        }

        $data->delete();

       Flash::success( __('auth.delete_account'));

        return redirect()->route('user.index');

    }

    /**
     * Datatables Ajax Data
     *
     * @return mixed
     * @throws \Exception
     */
    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $loginUser = Sentinel::getUser()->id;
            $dataDb = User::select([
                'users.id',
                'member_id',
                'first_name',
                'last_name',
                'email',
                'last_login',
                'users.created_at',
                'users.updated_at',
            ])
                ->where('id', '!=', $loginUser)
                ->with('roles', 'activations')
                ->whereHas('roles',function($q){
                    $q->whereIn('slug',['admin','superadmin']);
                });

            return DataTables::eloquent($dataDb)
                ->addColumn('action', function ($dataDb) {
                    return '<a href="' . route('user.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                            <a href="#" data-message="' . trans('auth.delete_confirmation', ['name' => $dataDb->email]) . '" data-href="' . route('user.destroy', $dataDb->id) . '" id="tooltip" data-method="DELETE" data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
                       
                })
                ->addColumn('role', function ($dataDb) {
                    if ($dataDb->roles->isNotEmpty()) {
                        return implode(', ', collect($dataDb->roles)
                            ->pluck('name')
                            ->all());
                    }
                })
                ->addColumn('status', function ($dataDb) {
                    if ($dataDb->activations->isNotEmpty()) {
                        if ($dataDb->activations[0]->completed == 1) {
                            return '<a href="#" data-message="' . trans('auth.deactivate_subheading', ['name' => $dataDb->email]) . '" data-href="' . route('user.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('auth.deactivate_this_user') . '" data-title-modal="' . trans('auth.deactivate_heading') . '" data-toggle="modal" data-target="#delete" title="' . trans('auth.deactivate_this_user') . '"><span class="label label-success label-sm">' . trans('auth.index_active_link') . '</span></a>';
                        }
                    }
                    return '<a href="#" data-message="' . trans('auth.activate_subheading', ['name' => $dataDb->email]) . '" data-href="' . route('user.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('auth.activate_this_user') . '" data-title-modal="' . trans('auth.deactivate_heading') . '" data-toggle="modal" data-target="#delete" title="' . trans('auth.activate_this_user') . '"><span class="label label-danger label-sm">' . trans('auth.index_inactive_link') . '</span></a>';
                })
                ->rawColumns(array(
                    'status',
                    'action'
                ))
                ->make(true);
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

                return redirect()->route('user.index');
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


    public function getUserDetails(Request $request)
    {
        $member_id = $request->memeber_id;
        $user = User::where('member_id',$member_id)
                    ->first();
        if (empty($user)) {
            return response(['status' => false, 'msg'=> trans('global.not_found')]);
        }
        return response(['status' => true, 'msg'=> '', 'data' => $user]);
    }

    public function editUser($id)
    {
        $user = Sentinel::getUser();
        if($user->id != $id) {
            Flash::error( __('global.something'));
            return redirect(route('user.edit-profile', $user->id));
        }
        if (empty($user)) {
           Flash::error( __('global.not_found'));
            return redirect()->route('user.index');
        }

        $roleDb = Role::pluck('name','id');

        $userRole = $user->roles[0]->id ?? null;
       
        return view('auth.user.edit-user', compact('user','roleDb','userRole'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = Sentinel::findById($id);
        $is_valid_user = Sentinel::stateless(array(
            'email'    => $user->email,
            'password' => $request->old_password,
        ));
        if($is_valid_user) {
            if ($request->new_password) {
                $validator = Validator::make($request->all(), [
                    'new_password' => 'min:5',
                ]);
                if ($validator->fails()) {
                    return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
                }
                $credentials = [
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'address'    => $request->address,
                    'password' => $request->new_password
                ];
               
                Sentinel::update($user, $credentials);
                Flash::success( __('auth.update_successful'));
                return redirect(route('user.edit-profile', $id));
            }
        }
        Flash::error( __('Please Enter valid old password'));
        return redirect(route('user.edit-profile', $id));
    }
}
