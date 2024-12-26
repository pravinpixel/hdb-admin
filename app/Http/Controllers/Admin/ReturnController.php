<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Item;
use App\Models\ReturnItem;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp_id = 'false';
        if(Sentinel::inRole('employee') || Sentinel::inRole('manager')) {
            $emp_id = Sentinel::getUser()->member_id;
        }
        return view('admin.return.index', compact('emp_id'));
    }

    public function getTakenItem(Request $request)
    {
        $user_id = $request->input('user_id');
        $item_id = $request->input('item_id');
        $item = Item::where('item_id', $item_id)->first();
        if( empty($item)) {
            return response(['status' => false, 'msg' => 'Item not found']);
        }
        $checkout = Checkout::where([
                'checkout_by' => $user_id, 
                'item_id' =>  $item->id,
                'return_status' => 0
                ])
                ->with('item')
                ->first();
        if( empty($checkout) ) {
            return response(['status' => false, 'msg' => 'Item not found']);
        }
        $data['item_name'] = $checkout->item->item_name ?? " ";
        $data['item_id'] = $checkout->item->item_id ?? " ";
        $data['date_of_return'] =  $checkout->date_of_return ?? " ";
        $data['checkout_id'] =  $checkout->id ?? " ";
        $data['item_image']  =  "<img src='".asset('uploads/'.$checkout->item->cover_image)."' border='0' width='100' class='img-rounded' align='center'>"; 
        return response(['status' => true, 'data' => $data, 'msg' => '']);
    }

    public function Checkin(Request $request)
    {
        $user_id = $request->user_id;
        $checkout_id = $request->checkout_id;
        $user = Sentinel::findById($user_id);
        $checkouts = Checkout::with('item')->find($checkout_id);
        $updated = false;
        if(!empty($user) && !empty($checkouts)) {
            foreach($checkouts as $checkout) {
                if(!$checkout->return_status) {
                    $return_item = new ReturnItem();
                    $return_item->item_id       = $checkout->item->id;
                    $return_item->checkout_id   = $checkout->id;
                    $return_item->returned_by   = $user->id;
                    $return_item->save();
                    $checkout->return_status = 1;
                    $checkout->return_id = $return_item->id;
                    $checkout->save();
                    $updated = $checkout->item()->update([
                        'is_issued' => self::RETURN_ITEM,
                        'checkout_by' => Null,
                        'date_of_return' => Null
                    ]);
                }
            }
            if($updated) {
                return response(['status' => true, 'msg' => 'Return successfully']);
            }
            return response(['status' => false, 'msg' => 'Item already return']);
        }
    }

    public function getAllTakenItem(Request $request)
    {
        $user_id = $request->user_id;
        $user = Sentinel::findById($user_id);
        $checkouts = Checkout::with('item')->where([
                                'checkout_by' => $user->id,
                                'return_status' => 0
                            ])
                            ->get();
        return view('admin.return.get-return-item', compact('checkouts'));
    }
}
