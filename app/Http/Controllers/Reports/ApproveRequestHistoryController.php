<?php

namespace App\Http\Controllers\Reports;

use App\Exports\ApproveHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\ApproveRequest;
use App\Models\User;
use App\Models\Item;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ApproveRequestHistoryController extends Controller
{
    public function index()
    {
        $status = Config::get('report.status');
        return view('admin.reports.approve-history.index', compact('status'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $where = [];
            if($request->member && $request->status) {
                $where[$request->status] = $request->member;
            }
            if($request->item) {
                $where['item_id'] = $request->item;
            }

            $data['total_item'] = ApproveRequest::when(!Sentinel::inRole('admin'), function($q) {
                $q->where('requested_by', Sentinel::getUser()->id);
            })->where($where)->get()->count();
            $data['total_rejected_item'] = ApproveRequest::when(!Sentinel::inRole('admin'), function($q) {
                $q->where('requested_by', Sentinel::getUser()->id);
            })->where($where)->where('approve_status', self::REQUEST_REJECTED)->get()->count();
            $data['total_approved_item'] = ApproveRequest::when(!Sentinel::inRole('admin'), function($q) {
                $q->where('requested_by', Sentinel::getUser()->id);
            })->where($where)->where('approve_status', self::REQUEST_ACCEPTED)->get()->count();

            $dataDb =  ApproveRequest::query();
            $dataDb->where($where)
                    ->when(!Sentinel::inRole('admin'), function($q) {
                        $q->where('requested_by', Sentinel::getUser()->id);
                    })
                    ->with('item','user','rejectedBy','approvedBy');
            return DataTables::eloquent($dataDb)
                    ->editColumn('created_at', function($dataDb){
                        return Carbon::parse($dataDb->created_at)->format('d-m-Y');
                    })
                    ->editColumn('requested_user', function($dataDb){
                        if($dataDb->user) {
                            return '<div class="text-left"><div class="badge badge-info">'.$dataDb->user->first_name .'</div></div>';
                        }
                        return '';
                    })
                    ->editColumn('approved_rejected_user', function($dataDb){
                        if($dataDb->rejectedBy) {
                            return '<div class="text-left"><div class="badge badge-danger">'.$dataDb->rejectedBy->first_name.'</div></div>';
                        } else if($dataDb->approvedBy) {
                            return '<div class="text-left"><div class="badge badge-success">'.$dataDb->approvedBy->first_name.'</div></div>';
                        }
                        return '';
                    })
                    ->rawColumns(['requested_user','approved_rejected_user'])
                    ->with(['card' => $data])
                    ->make(true);
        }
    }

    public function export(Request $request) 
    {
        return Excel::download(new ApproveHistoryExport($request), 'approve-request-history.xlsx');
    }


    public function getMemberDropdown(Request $request)
    {
        $query = $request->input('q');
        return User::where('first_name','like', '%' .  $query. '%') 
                        ->when(!Sentinel::inRole('admin'), function($q) {
                            $q->where('id', Sentinel::getUser()->id);
                        })
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => $row->first_name.' '.$row->last_name ];
                        });
    }

    public function getItemDropdown(Request $request)
    {
        $query = $request->input('q');
        return Item::where('item_name','like', '%' .  $query. '%') 
                        ->orWhere('item_id', 'like', '%' .  $query. '%')
                        ->where('is_active', 1)
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => $row->item_name];
                        });
    }
}
