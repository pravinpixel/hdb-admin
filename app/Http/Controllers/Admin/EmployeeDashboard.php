<?php

namespace App\Http\Controllers\Admin;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use App\Mail\ApproveRequest as EmailRequest;
use App\Models\ApproveRequest;
use App\Models\Checkout;
use App\Models\Config as ModelConfig;
use App\Models\Item;
use App\Models\Notification as ModelsNotification;
use App\Models\ReturnItem;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class EmployeeDashboard extends Controller
{
    public function index()
    {
        $data['total_item_taken'] = Checkout::where('checkout_by', Sentinel::getUser()->id)->distinct('item_id')->count();
        $data['total_item_return'] = ReturnItem::where('returned_by', Sentinel::getUser()->id)->distinct('item_id')->count();
        $data['total_item_to_be_return'] = Checkout::where([
                'checkout_by'=> Sentinel::getUser()->id,
                'return_status' => 0
                ])->distinct('item_id')->count();
        $data['total_item_requested'] = ApproveRequest::where('requested_by', Sentinel::getUser()->id)->distinct('item_id')->count();
        return view('employee.index', compact('data'));
    }

    public function approveRequest($id)
    {
        try {
            $config = ModelConfig::find(1);
            $approve = new RequestController();
            $item = Item::find($id);
            if(!empty($item)) {
                $result = $approve->approveRequest($item);
                if(!$result) {
                    return response(['status' => false, 'msg' => trans('global.something')]);
                }
                $item->status = self::REQUESTED;
                $item->save();
                $details = [
                    'title' => 'Approve Request',
                    'item'  => $item,
                    'user'  => Sentinel::getUser(),
                    'url'   => route('approval-list.index')
                ];
                $to_email = Config::get('email.manager_email');
                $role     = Sentinel::findRoleBySlug('manager')->first();
                $from     =  Sentinel::getUser()->id;
                $to       = $role->id;
                $item_id  = $item->id;
                if($config->enable_email == 1) {
                    Mail::to($to_email)->send(new EmailRequest($details));
                    if(count(Mail::failures()) > 0){
                        event(new Notification("Approve Request sent email failure [item name : {$item->item_name}]","approve_request_send", $id, $from, $to, $item_id,0));
                        Log::info('Approve Request sent email failure');
                    }else {
                        event(new Notification("Approve Request sent email successfully [item name : {$item->item_name}]",'send_approve_request', $id, $from, $to, $item_id));
                        Log::info('Approve Request sent email successfully');
                    }
                }
            }
            return response(['status' => true, 'msg' => trans('global.approve_request_send_successfully')], 200);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            event(new Notification("Approve Request sent email failure [item name : {$item->item_name}]", 'send_approve_request', $id, $from, $to, $item_id, 0));
            return response(['status' => true, 'msg' => trans('global.approve_request_send_successfully')]);
        }
    }

    public function requestForApproval(Request $request)
    {
        $user_id = Sentinel::getUser()->id;
        if ($request->ajax() == true) {
            $dataDb =  ApproveRequest::query();
            $dataDb->where('requested_by', $user_id)
                    ->with(['item'])
                    ->orderBy('id','desc');
            return DataTables::eloquent($dataDb)
                ->addColumn('item', function($dataDb) {
                    return $dataDb->item->item_name;
                })
                ->editColumn('created_at', function($dataDb) {
                    return $dataDb->created_at;
                })
                ->editColumn('approve_status', function($dataDb) {
                    $status = '';
                    if($dataDb->approve_status == 1){
                        $status .= '<div class="label label-info label-sm"> Pending </div>';
                    } else if($dataDb->approve_status == 2) {
                        $status .= '<div class="label label-success label-sm"> Approved </div>';
                    }else if($dataDb->approve_status == 3) {
                        $status .= '<div class="label label-danger label-sm"> Rejected </div>';
                    }
                    return $status;
                })
                ->rawColumns(['approve_status'])
                ->make(true);
        }
    }

    public function approvalRequestEmail($id , Request $request) {
        $approve_request = ApproveRequest::with('item')->find($id);
        $details = [
            'title' => 'Approve Request',
            'item'  => $approve_request->item,
            'user'  => Sentinel::getUser(),
            'url'   => route('approval-list.index')
        ];
        $to_email = Config::get('email.manager_email');
        Mail::to($to_email)->send(new EmailRequest($details));
        if(count(Mail::failures()) > 0){
            $approve_request->email_send = 0;
        } else {
            $approve_request->email_send = 1;
        }
        $approve_request->save();
    
    }

    public function pastItemTaken(Request $request)
    {
        $user_id = Sentinel::getUser()->id;

        if ($request->ajax() == true) {
            $dataDb =  Checkout::query();
            $dataDb->where(['checkout_by'=> $user_id, 'return_status' => 0])
                    ->with(['item'])
                    ->orderBy('id','desc');
            return DataTables::eloquent($dataDb)
                ->addColumn('item', function($dataDb) {
                    return $dataDb->item->item_name;
                })
                ->editColumn('date_of_return', function($dataDb) {
                    return Carbon::parse($dataDb->date_of_return)->diffForHumans();
                })
                ->addColumn('status', function($dataDb) {
                    $past_status = '';
                    $date_of_return = strtotime($dataDb->date_of_return);
                    $now = strtotime(now());
                    $day_diff = $date_of_return -$now;
                    if( $day_diff < 0) {
                        $past_status .= '<div class="label label-danger label-sm"> Overdue </div>';
                    } else {
                        $past_status .= '<div class="label label-success label-sm">  Available </div>';
                    }
                    return $past_status;
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }
}
