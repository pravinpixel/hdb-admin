<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AcceptRequest;
use App\Mail\ApproveRequest;
use App\Mail\RejectRequest;
use App\Mail\SendReminder;
use App\Models\Config as ModelConfig;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $start_date = $request->start_date ?? Carbon::now()->subDays(6);
        $end_date = $request->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        return view('admin.notification.index', compact('start_date','end_date'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.notification.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return view('admin.notification.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('admin.notification.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin.notification.edit');
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
        return view('admin.notification.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return view('admin.notification.index');
    }

    public function datatable( Request $request)
    {
        if ($request->ajax() == true) {
            $start_date = $request->start_date ?? Carbon::now()->subDays(6);
            $end_date = $request->end_date ?? now();
            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = Carbon::parse($end_date)->endOfDay();
            $dataDb = Notification::query()->with('user');
            if(!Sentinel::inRole('admin')) {
                $dataDb->where('created_by', Sentinel::getUser()->id); 
            }
            $dataDb->whereBetween('created_at', [$start_date, $end_date]);
            $dataDb->orderBy('type');
            return DataTables::eloquent($dataDb)
            ->editColumn('created_at', function($dataDb) {
                return Carbon::parse($dataDb->created_at)->format('d-m-Y');
            })
            ->editColumn('type', function($dataDb) {
                return Str::replace('_', ' ', $dataDb->type);
            })
            ->addColumn('action', function ($dataDb) {
                if($dataDb->status == 0) {
                    return '<a href="#" data-message="Are you sure to send email" data-href="'. route('notification.resend', $dataDb->id) .'" id="tooltip" data-method="POST" data-title="Send Email" data-title-modal="Send Overdue Email" data-toggle="modal" data-target="#delete" title=""><span class="label label-danger label-sm"> Resend </span></a>';
                }
                return '';
            })
            ->make(true);
        }
    }

    public function resend(Request $request, $id)
    {
        $config = ModelConfig::find(1);
        if($config->enable_email == 1) {
            $notification = Notification::find($id);
            if(empty($notification->from)) {
                return redirect(route('notification.index'));
            }
            $to_email = Sentinel::findById($notification->to);
            $from_email = Sentinel::findById($notification->from);
            $item = Item::find($notification->item_id);
            if($notification->type == "approval_request_accepted") {
                $details = [
                    'title'   => 'Request Approved',
                    'user'    => Sentinel::findById($notification->from),
                    'manager' => $from_email,
                    'item'   => $item
                ];
                    DB::beginTransaction();
                    try {
                        Mail::to($to_email)->send(new AcceptRequest($details));
                       
                        $notification->status = 1;
                        $notification->save();
                        DB::commit();
                        Flash::success('email send successfully');
                    } catch (Exception $e) {
                        DB::rollBack();
                        Flash::error(__('global.something'));
                        Log::error($e->getMessage());
                    }
                
            } else if ($notification->type == "approval_request_reject") {

                $details = [
                    'title'   => 'Request Rejected',
                    'user'    => $to_email,
                    'manager' => $from_email,
                    'item'    => $item
                ];
                DB::beginTransaction();
                try {
                    Mail::to($to_email)->send(new RejectRequest($details));
                  
                    $notification->status = 1;
                    $notification->save();
                    DB::commit();
                    Flash::success("{$details['title']} email send successfully");
                } catch (Exception $e) {
                    DB::rollBack();
                    Flash::error(__('global.something'));
                    Log::error($e->getMessage());
                }
            

            } else if ($notification->type == "overdue") {
                $current_date = strtotime(Date('Y-m-d'));
                $checkout_date = strtotime($item->date_of_return);
                $day_diff = ( $checkout_date - $current_date ) / 86400 ;
                $details = [
                    'title'   => 'Overdue Email',
                    'user'    => $to_email,
                    'item'    => $item,
                    'overdue' => $day_diff
                ];
                DB::beginTransaction();
                try {
                    Mail::to($to_email)->send(new SendReminder($details));
                   
                    $notification->status = 1;
                    $notification->save();
                    DB::commit();
                
                } catch (Exception $e) {
                    DB::rollBack();
                    Flash::error(__('global.something'));
                    Log::error($e->getMessage());
                }
            } else if ($notification->type == "send_approve_request") {
                $details = [
                    'title' => 'Approve Request',
                    'item'  => $item,
                    'user'  => $to_email,
                    'url'   => route('approval-list.index')
                ];
                DB::beginTransaction();
                try {
                    Mail::to($to_email)->send(new ApproveRequest($details));
                    $notification->status = 1;
                    $notification->save();
                    Flash::success('email send successfully');
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    Flash::error(__('global.something'));
                    Log::error($e->getMessage());
                }
        
            } else if( $notification->type ==  "user_registration_failure") {

            }
            return redirect(route('notification.index'));
        } else { 
            Flash::error('Please enable email configuration');
            return redirect(route('notification.index'));
        }
    }

}
