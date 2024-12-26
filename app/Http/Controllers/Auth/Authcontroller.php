<?php

namespace App\Http\Controllers\Auth;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\ApprovalRequest;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Authcontroller extends Controller
{
    public function getSignin()
    { 
        if (Sentinel::check()) {
            if(Sentinel::inRole('manager')) {
                return redirect(route('manager.dashboard'));
            } else if(Sentinel::inRole('employee')) {
                return redirect(route('employee.dashboard'));
            }
            return redirect(route('admin.dashboard'));
        }
        return view('auth.login');
    }

    public function postSignin (LoginRequest $request)
    {
        try {
            if($user = Sentinel::authenticate($request->only(['email','password']),$request->get('remember', false))) {
                Flash::success( __('auth.login_successful'));
                if(Sentinel::inRole('manager')) {
                    return redirect(route('manager.dashboard'));
                } else if(Sentinel::inRole('employee')) {
                    return redirect(route('employee.dashboard'));
                }
                return redirect(route('admin.dashboard'));
            } else {
                Flash::error( __('auth.incorrect_email_id_and_password'));
                return redirect(route('login'));
            }
        } catch (ThrottlingException $ex) {
            Flash::error(__('auth.login_timeout'));
            return redirect()->route('login');
            
        } catch (NotActivatedException $ex) {
            Flash::error(__('auth.login_unsuccessful_not_active'));
            
            return redirect()->route('login');
        }
    }
    
    public function postSignout () 
    {
        Sentinel::logout(null, true);
        Flash::success(__('auth.logout_successful'));
        return redirect(route('login'));
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendForgotEmail(ForgotPasswordRequest $request)
    {

        try {
            $credentials = [
                'login' => $request->email,
            ];
    
            $user = Sentinel::findByCredentials($credentials);
    
            if (!$user) {
        
                Flash::error(  __('auth.forgot_password_email_not_found'));
        
                return redirect()->back()
                                 ->withInput();
            }
            $details = [
                'title' => 'Forgot Password',
                'user'  => $user,
                'url'   => route('user.index')
            ];
            $admin_email = Config::get('email.admin_email');
            Mail::to($admin_email)->send(new ForgotPassword($details));
            if(count(Mail::failures()) > 0){
                event(new Notification("Forget email sent failure [from - {$user->email}]", "forgot_password_failure", $user->id,$user->id,1,null,0));
                Log::error('Failed to send password reset email, please try again.');
            }else {
                event(new Notification("forgot email send successfully [from - {$user->email}]", "forgot_password_success", $user->id,$user->id,1,null));
                Log::info('forgot email send successfully');
            }   
            Flash::success( __('auth.forgot_password_successful'));
            return redirect(route('login'));
            
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            Flash::error( __('auth.forgot_password_unsuccessful'));
            return redirect()->back();
        }
   
    }
}
