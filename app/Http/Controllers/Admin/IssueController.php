<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Item;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class IssueController extends Controller
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
        return view('admin.issue.index', compact('emp_id'));
    }

    public function datatable($id, Request $request)
    {
        if ($request->ajax() == true) {
            $dataDb =  Issue::query()
                        ->where([
                            'received_by' => $id,
                            'issue_status' => 0
                        ])
                        ->with(['item','item.category','item.subcategory','item.genre','item.type']);    
            return DataTables::eloquent($dataDb)
                ->addColumn('checkbox', function($dataDb){
                    return '<input type="checkbox" data-issue-id="'.$dataDb->id.'" class="issue-checkbox form-control">';
                })
                ->addColumn('action', function($dataDb){
                    return '<button data-issue-id="'.$dataDb->id.'" class="remove-checkout btn btn-danger"> Remove </button>';
                })
            ->rawColumns(['action'])
            ->make(true);
        }
    }

    public function getItemApproval(Request $request)
    {
        $item_id = $request->input('item');
        $type = $request->input('type');
        $user_id = $request->input('user_id');
        $item = Item::with(['category','subcategory','genre','type','user','approveRequestByUser'])
                    ->where('item_id' , $item_id)
                    ->where('is_active', 1)
                    ->orWhere('item_name', $item_id)
                    ->first();
        if( empty( $item ) ) {
            return response(['status' => false, 'msg' => trans('global.not_found')], 404);
        }
        if($item->approveRequestByUser) {
            $approval_request = $item->approveRequestByUser()->where([
                'requested_by' => $user_id
            ])->orderBy('id','desc')->first();
        }
      
        $data['item'] = $item;
        $data['approval_request'] = $approval_request ?? null;
        $data['waiting_for_request'] = $approval_request->approve_status ?? null;
        if($type == 'json'){
            return response(['status'=> true, 'data' => $data]);
        }
        return response(['status' => false, 'msg' => trans('global.not_found')]);
    }
}
