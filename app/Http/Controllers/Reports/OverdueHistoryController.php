<?php

namespace App\Http\Controllers\Reports;

use App\Exports\OverdueHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Config;

class OverdueHistoryController extends Controller
{
    public function index()
    {
        return view('admin.reports.overdue-history.index');
    }

    public function datatable(Request $request)
    {
    
        if ($request->ajax() == true) {

            $dataDb = Checkout::query();
            if($request->member_id) {
                $dataDb->where('checkout_by', $request->member_id);
            }
            $start_date = $request->start_date ?? now();
            $end_date = $request->end_date ?? now();
            $start_date = Carbon::parse($start_date)->format('Y-m-d');
            $end_date = Carbon::parse($end_date)->format('Y-m-d');

            $dataDb->where('return_status',false)
                    ->whereBetween('date_of_return', [$start_date, $end_date])
                    ->with(['user','item']);
            $count = $dataDb;
            $data['overdue'] = $count->get()->count();
            return DataTables::eloquent($dataDb)
                    ->editColumn('created_at',  function($dataDb) {
                        if($dataDb->created_at) {
                             return Carbon::parse($dataDb->created_at)->format('d-m-Y');
                        }
                    })
                    ->addColumn('overdue_days',  function($dataDb) {
                        $day_diff = 0;
                        if($dataDb->date_of_return) {
                            $current_date = strtotime(Date('Y-m-d'));
                            $checkout_date = strtotime($dataDb->date_of_return);
                            $day_diff = ( $checkout_date - $current_date ) / 86400 ;
                        }
                        return "<div class='text-center'><div class='badge badge-danger'>{$day_diff} days </div></div>" ;
                    })
                    ->editColumn('date_of_return',  function($dataDb) {
                        if($dataDb->date_of_return) {
                             return Carbon::parse($dataDb->date_of_return)->format('d-m-Y');
                        }
                    })
                    ->rawColumns(['overdue_days'])
                    ->with(['card' => $data])
                    ->make(true);
        }
    }

    public function export(Request $request) 
    {
        return Excel::download(new OverdueHistoryExport($request), 'overdue-history.xlsx');
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
}
