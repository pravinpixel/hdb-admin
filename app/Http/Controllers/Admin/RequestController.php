<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AcceptRequest;
use App\Models\ApproveRequest;
use App\Models\Issue;
use App\Models\Item;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RequestController extends Controller
{
    public function approveRequest(Item $item)
    {
        $approveReq = new ApproveRequest();
        $approveReq->item_id = $item->id;
        $approveReq->requested_by = Sentinel::getUser()->id;
        $approveReq->approve_status = self::REQUESTED;
        $approveReq->save();
        return $approveReq->id;
    }

    public function approvedRequest(ApproveRequest $approveReq)
    {
        $item = Item::find($approveReq->item_id);
        if(empty($item)){
            return false;
        }
        $item->status = self::REQUEST_ACCEPTED;
        $item->is_issued = false;
        $item->save();
        $date_of_return = Carbon::now()->addDays($item->loan_days);
        $approveReq->approved_by = Sentinel::getUser()->id;
        $approveReq->approve_status = self::REQUEST_ACCEPTED;
        $approveReq->date_of_return =  $date_of_return;
        return $approveReq->save();
        
    }

    public function rejectRequest(ApproveRequest $approveReq)
    {
        $item = Item::find($approveReq->item_id);
        if(empty($item)){
            return false;
        }
        $item->status = self::REQUEST_REJECTED;
        $item->save();
        $approveReq->rejected_by = Sentinel::getUser()->id;
        $approveReq->approve_status = self::REQUEST_REJECTED;
        return $approveReq->save();
    }

    public function issueItem($item, $approveReq)
    {
        $issue = new Issue();
        $issue->item_id =  $item->id;
        $issue->issue_date = Carbon::now();
        $issue->approve_request_id =  $approveReq->id;
        $issue->approved_by =  Sentinel::getUser()->id;
        $issue->received_by = $approveReq->requested_by;
        $issue->date_of_return =  Carbon::now()->addDays($item->loan_days);
        $issue->issue_status = self::ISSUE_ITEM;
        return $issue->save();
    }
}
