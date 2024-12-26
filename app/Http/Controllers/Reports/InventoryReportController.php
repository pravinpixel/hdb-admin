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
                $dataDb->where('item_id',$request->search_item_name);
                $dataDb->orWhere('item_name',$request->search_item_name);
            } 
            if($request->category) {
                $dataDb->where('category_id',$request->category);
            }
            if($request->subcategory) {
                $dataDb->where('subcategory_id',$request->subcategory);
            }
            $data['total_item'] = Item::get()->count();
            $data['total_active_item'] = Item::where('is_active',1)->get()->count();
            $data['total_inactive_item'] =  $data['total_item'] -  $data['total_active_item'];
            $data['total_issued_item'] = Item::where('is_issued',1)->get()->count();
            $dataDb->with('category','subcategory','type','genre','user');
            return DataTables::eloquent($dataDb)
                    ->addColumn('status', function($dataDb) {
                        return ($dataDb->is_issued == 1) ? '<div class="text-left"><div class="badge badge-warning"> N/A </div></div>' : '<div class="text-left"><div class="badge badge-success"> Available </div></div>';
                    })
                    ->addColumn('issued_to', function($dataDb) {
                        if($dataDb->is_issued == 1 && $dataDb->user != null) {
                            return '<div class="text-left"><div class="badge badge-success">' .$dataDb->user->full_name. '</div></div>';
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
