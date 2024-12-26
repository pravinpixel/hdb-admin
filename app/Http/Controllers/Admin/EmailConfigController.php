<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailConfiguration;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Laracasts\Flash\Flash;

class EmailConfigController extends Controller
{
    public function create()
    {
        $emailConfig = EmailConfiguration::find(1);
        return view('email-config.create', compact('emailConfig'));
    }

    public function update($id, Request $request) {

        $emailConfig = EmailConfiguration::find($id);
        $emailConfig->driver       =      $request->driver;
        $emailConfig->host         =      $request->host;
        $emailConfig->port         =      $request->port;
        $emailConfig->encryption   =      $request->encryption;
        $emailConfig->user_name    =      $request->user_name;
        $emailConfig->password     =      Crypt::encryptString($request->password);
        $emailConfig->sender_name  =      $request->sender_name;
        $emailConfig->sender_email =      $request->sender_email;
        $emailConfig->save();
        if(!is_null($emailConfig)) {
            Flash::success(__('global.updated'));
            return redirect(route('admin.dashboard'));
        }
        Flash::error(__('global.somethig'));
        return redirect(route('email-config.create'));
    }

    public function getGeneratePassword(Request $request)
    {
        if($request->has('data') && Sentinel::check() == true && $request->ajax() == true){
            $decrypt = Crypt::encryptString($request->input('data'));
            return ['status' => true, 'msg' => '', 'data' => $decrypt ];
        }
        return ['status' => false, 'msg' => __('global.something'), 'data' => '' ];
    }
}
