<?php

namespace App\Http\Controllers\Reports;

use App\Exports\InventoryExport;
use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class InventoryReportController extends Controller
{
    public function index()
    {
    
        return view('admin.reports.inventory.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $dataDb =  Item::query();
            if($request->search_item_name) {
                $dataDb->where('title','like', '%' . $request->search_item_name. '%');
                $dataDb->orWhere('item_ref','like', '%' . $request->search_item_name. '%');
                $dataDb->orWhere('isbn','like', '%' . $request->search_item_name. '%');
                $dataDb->orWhere('author','like', '%' . $request->search_item_name. '%');
                $dataDb->orWhere('call_number','like', '%' . $request->search_item_name. '%');
                $dataDb->orWhere('location','like', '%' . $request->search_item_name. '%');
            } 
            if($request->language) {
                $dataDb->where('language',$request->language);
            }
            $data['total_item'] = Item::get()->count();
            $data['total_active_item'] = Item::where('is_active',0)->get()->count();
            $data['total_inactive_item'] =  $data['total_item'] -  $data['total_active_item'];
            $data['total_issued_item'] = Item::where('is_active',1)->get()->count();
            $dataDb->with('language','user');
            return DataTables::eloquent($dataDb)
                    // ->addColumn('status', function($dataDb) {
                    //     return ($dataDb->status == 1) ? '<div class="text-left"><div class="badge badge-warning"> Taken </div></div>' : '<div class="text-left"><div class="badge badge-success"> Available </div></div>';
                    // })
                    ->addColumn('status', function($dataDb) {
                        if($dataDb->is_active == 1 && isset($dataDb->checkout) && isset($dataDb->checkout->user) && !empty($dataDb->checkout->user->first_name)) 
                            return '<div class="text-left"><div class="badge badge-warning"> Taken </div></div>';
                        else if ($dataDb->status == 1) 
                            return '<div class="text-left"><div class="badge badge-success"> Available </div></div>';
                        else
                            return '<div class="text-left"><div class="badge badge-info"> Un-Available </div></div>';
                    })
                    ->addColumn('issued_to', function($dataDb) {
                        if($dataDb->is_active == 1 && isset($dataDb->checkout) && isset($dataDb->checkout->user) && !empty($dataDb->checkout->user->first_name)) {
                            return '<div class="text-left"><div class="badge badge-success">' .$dataDb->checkout->user->first_name. '</div></div>';
                        }
                        return '<div class="text-left"><div class="badge badge-info"> Nill </div></div>';
                    })
                    ->rawColumns(['issued_to','status'])
                    ->with(['card' => $data])
                    ->make(true);
        }
    }

    public function export(Request $request) 
    {
        return Excel::download(new InventoryExport($request), 'inventory.xlsx');
    }
}
