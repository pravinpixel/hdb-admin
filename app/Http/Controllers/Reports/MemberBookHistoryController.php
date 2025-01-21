<?php
namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\User;
use App\Models\ApproveRequest;
use App\Models\Item;
use App\Exports\MemberViewHistoryExport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class MemberBookHistoryController extends Controller
{
    public function index()
    {
        return view('admin.reports.member-book-history.index');
    }

    public function datatable(Request $request)
    {
        if($request->ajax() == true) {
            $item_id = $request->item_id ?? null;
            $member_id = $request->member_id ?? null;
            $start_date = $request->start_date ?? now();
            $end_date = $request->end_date ?? now();
            $start_date = Carbon::parse($start_date)->format('Y-m-d');
            $end_date = Carbon::parse($end_date)->format('Y-m-d');
            $data['total_taken_item'] = Checkout::when($item_id, function($q) use ($item_id){
                    $q->where('item_id', $item_id);
                })->when(!Sentinel::inRole('admin'), function($q) {
                    $q->where('checkout_by', Sentinel::getUser()->id);
                })->when($member_id, function($q) use($member_id){
                    $q->where('checkout_by', $member_id);
                })->where('status', 'taken')
                ->whereBetween('date', [$start_date, $end_date])->get()->count();

            $data['total_return_item'] = Checkout::when($item_id, function($q) use ($item_id){
                    $q->where('item_id', $item_id);
                })->when(!Sentinel::inRole('admin'), function($q) {
                    $q->where('checkout_by', Sentinel::getUser()->id);
                })->when($member_id, function($q) use($member_id){
                    $q->where('checkout_by', $member_id);
                })->where('status', 'returned')
                ->whereBetween('date', [$start_date, $end_date])->get()->count();
            $dataDb = Checkout::query()
                        ->with(['item','user'])
                        ->when(!Sentinel::inRole('admin'), function($q) {
                            $q->where('checkout_by', Sentinel::getUser()->id);
                        });
                        if($item_id != null) {
                            $dataDb->where('item_id', $request->item_id);
                        }
                        if($member_id != null) {
                            $dataDb->where('checkout_by', $request->member_id);
                        }
                        $dataDb->whereBetween('date', [$start_date, $end_date]);
                        $dataDb->orderBy('checkout_by');

            return DataTables::eloquent($dataDb)
                    ->editColumn('created_at', function($dataDb){
                        return Carbon::parse($dataDb->created_at)->format('d-m-Y');
                    })
                    ->editColumn('date', function($dataDb){
                        return Carbon::parse($dataDb->date)->format('d-m-Y');
                    })
                    ->editColumn('date_of_return', function($dataDb){
                        return Carbon::parse($dataDb->date_of_return)->format('d-m-Y');
                    })
                    ->with(['card' => $data])
                    ->make(true);
        }
    }

    public function export(Request $request)
    {
        return Excel::download(new MemberViewHistoryExport($request), 'member-wise-view-history-report-.xlsx');
    }

    public function getMemberDropdown(Request $request)
    {
        $query = $request->input('q');
        return User::where('first_name','like', '%' .  $query. '%')
        ->where('role',7)
                    ->limit(25)
                    ->get()
                    ->map(function($row) {
                        return  ["id" => $row->id, "text" => $row->first_name.' '.$row->last_name ];
                    });
    }

    public function getItemDropdown(Request $request)
    {
        $query = $request->input('q');
        return Item::where('title','like', '%' .  $query. '%')
                        ->orWhere('item_ref', 'like', '%' .  $query. '%')
                        ->where('status', 1)
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => $row->title];
                        });
    }
}
