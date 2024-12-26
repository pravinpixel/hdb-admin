<?php

namespace App\Http\Controllers\Admin;

use App\Events\Notification;
use App\Http\Controllers\Controller;
use App\Mail\AcceptRequest;
use App\Mail\RejectRequest;
use App\Models\ApproveRequest;
use App\Models\Config;
use App\Models\Item;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class ApprovalController extends Controller
{
    public function index()
    {
    	return view('admin.approval.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $dataDb =  ApproveRequest::query();
            $dataDb->where('approve_status',1)
                    ->with(['item','user']);
            return DataTables::eloquent($dataDb)
                ->addColumn('created_at', function($dataDb){
                    return Carbon::parse($dataDb->created_at)->diffForHumans();
                })
                ->addColumn('item_id', function($dataDb){
                    return  $dataDb->item->item_id;
                })  
                ->addColumn('item_name', function($dataDb){
                    return $dataDb->item->item_name;
                })  
                ->addColumn('requested_by', function($dataDb){
                    return $dataDb->user->full_name;
                })
                ->addColumn('category', function($dataDb){
                    return $dataDb->item->category->category_name;
                })  
                ->addColumn('subcategory', function($dataDb){
                    return $dataDb->item->subcategory->subcategory_name;
                }) 
                ->addColumn('genre', function($dataDb){
                    return $dataDb->item->genre->genre_name;
                }) 
                ->addColumn('is_issued', function($dataDb) {
                    return ($dataDb->item->is_issued) ? '<a class="label label-info label-sm"> N/A </a>' : '<a class="label label-success label-sm"> Available </a>';
                })
                ->addColumn('approval_item', function($dataDb){
                    return "<input type='checkbox' data-approval-id={$dataDb->id} name='approval-item' class='form-control approval-item'>";
                })
                ->addColumn('action', function ($dataDb) {
                    return '<a href="#" class="approve-item" data-message="' . trans('employee.are_you_want_to_process', ['name' => $dataDb->item->item_id]) . '" data-href="' . route('approval-list.approve-request', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="approve request" data-toggle="modal" data-target="#delete"> <span class="btn btn-success btn-sm"> Approve  </span></a>
                    <a href="#" class="reject-item" data-message="' . trans('employee.are_you_want_to_process', ['name' => $dataDb->item->item_id]) . '" data-href="' . route('approval-list.reject-request', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="reject approve request" data-toggle="modal" data-target="#delete"> <span class="btn btn-danger btn-sm"> Reject  </span></a>';
                })
                ->rawColumns(['is_issued','approval_item','action'])
                ->make(true);
        }
    }

    public function approveRequest($id)
    {
        try {
            $config = Config::find(1);
            $approve = new RequestController();
            $approveReq = ApproveRequest::find($id);
            if(empty($approveReq)) {
                return response(['status' => false, 'msg' => trans('global.not_found')], 404);
            }
            if($approveReq->approve_status != 1) {
                return response(['status' => false, 'msg' => trans('global.already_item_processed')]);
            }
            $result = $approve->approvedRequest($approveReq);
            if(!$result) {
                return response(['status' => false, 'msg' => trans('global.already_item_issued')]);
            }
          
            $email = $approveReq->user->email;
            $from = Sentinel::getUser()->id;
            $to = $approveReq->user->id;
            $datails = [
                'title'   => 'Request Approved',
                'user'    => Sentinel::findById($approveReq->requested_by),
                'manager' => Sentinel::getUser(),
                'item'   => $approveReq->item
            ];
            if($config->enable_email == 1) {
                Mail::to($email)->send(new AcceptRequest($datails));
                if(count(Mail::failures()) > 0){
                    event(new Notification("Request Approved sent failure [item name : {$approveReq->item->item_name}]", "approval_request_accepted", $id, $from, $to, $approveReq->item->id,0));
                    Log::info('Request Approved sent failure');
                }else {
                    event(new Notification("Request Approved sent successfully [item name : {$approveReq->item->item_name}]", 'approval_request_accepted', $id, $from, $to, $approveReq->item->id));
                    Log::info('Request Approved sent successfully');
                }
            }
            return response(['status' => true, 'msg' => trans('global.approved_request_successfully')], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            event(new Notification("Request Approved sent failure [item name : {$approveReq->item->item_name}]", "approval_request_accepted", $id, $from, $to, $approveReq->item->id,0));
            return response(['status' => true, 'msg' => trans('global.approved_request_successfully')], 200);
        }
    }

    public function rejectRequest($id)
    {
        try {
            $config = Config::find(1);
            $approve = new RequestController();
            $approveReq = ApproveRequest::find($id);
            if(empty($approveReq)) {
                return response(['status' => false, 'msg' => trans('global.not_found')], 404);
            }
            if($approveReq->approve_status != 1) {
                return response(['status' => false, 'msg' => trans('global.already_item_processed')]);
            }
            $result = $approve->rejectRequest($approveReq);
            if(!$result) {
                return response(['status' => false, 'msg' => trans('global.something')]);
            }
            $role = Sentinel::findRoleBySlug('manager')->first();
            $email = $approveReq->user->email;
            $from = Sentinel::getUser()->id;
            $to = $approveReq->user->id;
            $datails = [
                'title'   => 'Request Rejected',
                'user' => Sentinel::findById($approveReq->requested_by),
                'manager' => Sentinel::getUser(),
                'item'   => $approveReq->item
            ];
            if($config->enable_email == 1) {
                Mail::to($email)->send(new RejectRequest($datails));
                if(count(Mail::failures()) > 0){
                    event(new Notification("Request Rejected sent email failure [item name : {$approveReq->item->item_name}]","approval_request_reject", $id, $from, $to, $approveReq->item->id,0));
                    Log::info('Request Rejected sent email failure');
                }else {
                    event(new Notification("Request Rejected sent email successfully [item name : {$approveReq->item->item_name}]",  $id, $from, $to, $approveReq->item->id));
                    Log::info('Request Rejected sent email successfully');
                }
            }
            return response(['status' => true, 'msg' => trans('global.reject_request_successfully')], 200);
        } catch (Exception $e) {
            Log::info($e->getMessage());
            event(new Notification("Request Rejected sent email failure [item name : {$approveReq->item->item_name}]", "approval_request_reject",  $id, $from, $to, $approveReq->item->id,0));
            return response(['status' => true, 'msg' => trans('global.reject_request_successfully')], 200);
        }
    }

    public function approveAllRequest(Request $request)
    {   
       try {
            $config = Config::find(1);
            $approveReqs = ApproveRequest::find($request->id)->where('approve_status',1);
            foreach($approveReqs as $approveReq) {
                $approveReq->approved_by = Sentinel::getUser()->id;
                $approveReq->approve_status = self::REQUEST_ACCEPTED;
                $approveReq->save();
                $email = $approveReq->user->email;
                $from = Sentinel::getUser()->id;
                $to = $approveReq->user->id;
                $datails = [
                    'title'   => 'Request Approved',
                    'user'    => Sentinel::findById($approveReq->requested_by),
                    'manager' => Sentinel::getUser(),
                    'item'   => $approveReq->item
                ];
                if($config->enable_email == 1) {
                    Mail::to($email)->send(new AcceptRequest($datails));
                    if(count(Mail::failures()) > 0){
                        event(new Notification("Request Approved sent failure [item name : {$approveReq->item->item_name}]", "approval_request_accepted", $$approveReq->id, $from, $to, $approveReq->item->id,0));
                        Log::info('Request Approved sent failure');
                    }else {
                        event(new Notification("Request Approved sent successfully [item name : {$approveReq->item->item_name}]",  $approveReq->id, $from, $to, $approveReq->item->id));
                        Log::info('Request Approved sent successfully');
                    }
                }
            }
            return response(['status' => true, 'msg' => trans('global.approved_request_successfully')], 200);
        }catch( \Exception $ex) {
            return response(['status' => false, 'msg' => trans('global.something')]);
        }
    }
}
