<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApproveRequest;
use App\Models\Checkout;
use App\Models\Issue;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;

class CheckoutController extends Controller
{
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Processes the checkout of items for a user.
     *
     * This method handles the checkout of items by verifying user identity,
     * checking for item approval requirements, and updating the checkout
     * records. It ensures that items needing approval have been approved before
     * proceeding with the checkout process. If successful, it updates the item
/******  cea1213d-234e-470e-be9d-adcebc3aae44  *******/

    public function CheckOut(Request $request)
    {
        $user_id = $request->user_id;
        $item_id = $request->item_id;
        $user = Sentinel::findById($user_id);
        $items = Item::find($item_id);
        $need_approval_item = Item::whereIn('id',$item_id)->where('is_need_approval', 1)
                            ->pluck('id','item_id')
                            ->toArray();
        $approve = ApproveRequest::whereIn('item_id', array_values($need_approval_item))
                        ->where(['requested_by' => $user->id,  'approve_status' => 2])
                        ->pluck('id')
                        ->toArray();
        if(count($need_approval_item) !=  count($approve) ) {
            return response(['status' => false, 'msg' => array_keys($need_approval_item)[0].' - item need approval']);
        }
        $insert = false;
        if(!empty($user) && !empty($items)) {
            foreach($items as $item) {
                if(!$item->is_issued) {
                    $checkout = new Checkout();
                    $checkout->date           = now();
                    $checkout->item_id        = $item->id;
                    $checkout->date_of_return = Carbon::now()->addDays($item->loan_days);
                    $checkout->checkout_by    = $user->id;
                    $insert = $item->checkouts()->save($checkout);
                    $item->is_issued = self::ISSUE_ITEM_CHECKOUT;
                    $item->checkout_by = $user->id;
                    $item->date_of_return =  $checkout->date_of_return;
                    $item->save();
                }
            }
            if($insert) {
                return response(['status' => true, 'msg' => 'Checkout done successfully']);
            }
            return response(['status' => false, 'msg' => 'Item already checkout']);
        }
    }

    public function sendApprovalRequest(Request $request)
    {
        $member_id = $request->member_id;
        $items_id = $request->item_id;
        $user = User::where('member_id', $member_id)->first();
        $items = Item::find($items_id);
        $insert = false;
        if(!empty($user) && !empty($items)) {
            foreach($items as $item) {
                $insert = ApproveRequest::updateOrCreate([
                    'item_id'        => $item->id,
                    'requested_by'   => $user->id,
                    'approve_status' => self::REQUESTED
                ]);
                Log::info("Checkout page: Approval Request send from {$user->first_name} : item name {$item->item_id} ");
            }
        }
        if($insert) {
            return response(['status' => true, 'msg' => 'Item(s) sent to approval successfully']);
        }
        return response(['status' => false, 'msg' => 'Something went wrong']);
    }
}
