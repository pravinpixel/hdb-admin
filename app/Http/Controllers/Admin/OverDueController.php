<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Checkout;
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
class OverDueController extends Controller
{
    public function index()
    {
        return view('admin.over-due.index');
    }
    public function datatable( Request $request)
    {
        if ($request->ajax() == true) {
            $start_date = $request->start_date ?? Carbon::now()->subDays(6);
            $end_date = $request->end_date ?? now();
            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = Carbon::parse($end_date)->endOfDay();
            $dataDb = Checkout::query()->with('item','user');
            $dataDb->whereBetween('date', [$start_date, $end_date]);
            $dataDb->orderBy('date', 'desc');
            return DataTables::eloquent($dataDb)
            ->editColumn('date', function($dataDb) {
                return Carbon::parse($dataDb->date)->format('d-m-Y');
            })
            ->editColumn('date_of_return', function($dataDb) {
                return Carbon::parse($dataDb->date_of_return)->format('d-m-Y');
            })
            ->editColumn('status', function($dataDb) {
               if($dataDb->status=='taken'){
                return '<span class="label label-warning label-sm">Taken</span>';
               }elseif($dataDb->status=='returned'){
                return '<span class="label label-success label-sm">Returned</span>';
               }else{
               return '<span class="label label-primary label-sm">Pending</span>';
               }
            })
            ->addColumn('action', function ($dataDb) {
                  return '<a href="' . route('item.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>';
            })
            ->rawColumns(['status','action'])
            ->make(true);
           
        }
    }
}
