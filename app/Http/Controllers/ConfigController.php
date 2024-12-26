<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = Config::find(1);
        return view('auth.config.index', compact('config'));
    }

    public function store(Request $request)
    {
        $config = Config::find(1);
        $config->enable_email = $request->enable_email == 'on' ? 1 : 0;
        if($config->save()) {
            Flash::success('Config updated successfully');
        } else {
            Flash::error(__('global.something'));
        }
        return redirect(route('config.index'));
    }

    public function callOverdueCron()
    {
        Artisan::call('schedule:run');
        $config = Config::find(1);
        $config->last_cron_updated = now();
        $config->save();
        Flash::success('Trigger overdue mail completed');
        return redirect(route('config.index'));
    }

    
}
