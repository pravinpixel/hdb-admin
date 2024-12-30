<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApproveRequest;
use App\Models\Checkout;
use App\Models\Issue;
use App\Models\Item;
use App\Models\Notification;
use App\Models\ReturnItem;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function index()
    {
        $current = Carbon::now();
        $today = $current->format('d-m-Y');
        $tomorrow = $current->addDays(1)->format('d-m-Y');
        $start_week = $current->startOfWeek()->format('d-m-Y');
        $end_week = $current->endOfWeek()->format('d-m-Y');
        $data['total_item'] = Item::get()->count();
        $data['total_issued'] = Checkout::get()->count();
        $data['total_item_to_be_returned_today'] = Checkout::whereBetween('date_of_return', array($today, $today))->get()->count();
        $data['total_item_to_be_returned_tomorrow'] = Checkout::whereBetween('date_of_return', array($tomorrow, $tomorrow))->get()->count();
        $data['total_item_to_be_returned_week'] = Checkout::whereBetween('date_of_return', array($start_week, $end_week))->get()->count();
        return view('admin.dashboard.index', compact('data'));
    }
    
    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $dataDb =  Item::query();
            if($request->search_item_name) {
                $dataDb->where('id', 'like', '%'.$request->search_item_name.'%');
                $dataDb->orWhere('title', 'like', '%'.$request->search_item_name.'%');
            }
            $dataDb->with('user');
            return DataTables::eloquent($dataDb) 
                ->addColumn('status', function ($dataDb) {
                    $status = '';
                    if($dataDb->is_active==1) {
                        $status = '<div class="text-left"><div class="badge badge-danger"> Issued </div> <div class="badge badge-danger"> '. $dataDb->user->full_name.' </div>   </div>';
                    } else {
                        $status = '<div class="text-left"><div class="badge badge-info"> Available </div></div>';
                    }
                   return  $status;
                }) 
                ->addColumn('date_of_return', function ($dataDb) {
                    $day_diff = 0;
                    if($dataDb->date_of_return) {
                        $current_date = strtotime(Date('Y-m-d'));
                        $checkout_date = strtotime($dataDb->date_of_return);
                        $day_diff = ( $checkout_date - $current_date ) / 86400 ;
                        $no_of_days = ($day_diff > 0) ? "<div><div class='label label-success'>{$day_diff} days</div></div>" : "<div><div class='label label-danger'>{$day_diff} days</div></div>";
                        return Carbon::parse($dataDb->date_of_return)->format('d-m-Y').'  '. $no_of_days;
                    }
                    return '-';
                }) 
                ->rawColumns(['status','date_of_return'])
                ->make(true);
        }
    }

    public function getNotification()
    {
        $notifications = Notification::with('user')
                ->when(!Sentinel::inRole('admin'), function($q){
                $q->where('created_by', Sentinel::getUser()->id);
            })->limit(5)->get();
        return view('admin.dashboard.notification', compact('notifications'));
    }
}
