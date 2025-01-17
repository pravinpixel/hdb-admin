<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\User;
use App\Models\Item;
use App\Exports\BookViewHistoryExport;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class BookWiseViewHistoryController extends Controller
{
    public function index(Request $request)
    {
        
        $start_date = $request->start_date ?? Carbon::now()->subDays(6);
        $end_date = $request->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        $items = Item::with(['language','checkouts'])
                ->when($request->item , function($q) use($request) {
                    $q->where('id', $request->item );
                })
                ->whereHas('checkouts', function($q) use ($start_date, $end_date) {
                    $q->when(!Sentinel::inRole('admin'), function($q) {
                        $q->where('checkout_by', Sentinel::getUser()->id);
                    })->whereBetween('date', [$start_date, $end_date]);
                })->paginate(10);
        $item_id = $request->item ?? null;
        $item = Item::find($item_id);
        return view('admin.reports.book-view-history.index', compact('items','start_date','end_date','item'));
    }

    public function datatable(Request $request)
    {
        if($request->ajax() == true) {
            $item_id = $request->item_id ?? null;
            $member_id = $request->member_id ?? null;
            $data['issued'] = Checkout::where('return_status', false)
                ->when(!Sentinel::inRole('admin'), function($q) {
                    $q->where('id', Sentinel::getUser()->id);
                })->when($item_id, function($q) use($item_id) {
                    $q->where('item_id', $item_id);
                })->when($member_id, function($q) use ($member_id) {
                    $q->where('checkout_by',$member_id);
                })->get()->count();

            $data['returned'] = Checkout::where('return_status', true)
                    ->when(!Sentinel::inRole('admin'), function($q) {
                        $q->where('id', Sentinel::getUser()->id);
                    })
                    ->when($item_id, function($q) use($item_id) {
                        $q->where('item_id', $item_id);
                    })->when($member_id, function($q) use ($member_id) {
                        $q->where('checkout_by',$member_id);
                    })->get()->count();
            
            $dataDb = Checkout::query()
                        ->with(['item','user'])
                        ->when(!Sentinel::inRole('admin'), function($q) {
                            $q->where('checkout_by', Sentinel::getUser()->id);
                        });
            if($item_id) {
                $dataDb->where('item_id', $request->item_id);
            } 
            if($member_id) {
                $dataDb->where('checkout_by', $request->member_id);
            }
            $dataDb->orderBy('item_id');       
            return DataTables::eloquent($dataDb)
                    ->editColumn('created_at', function($dataDb){
                        return Carbon::parse($dataDb->created_at)->format('d-m-Y');
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
        return Excel::download(new BookViewHistoryExport($request), 'book-wise-view-history-report-.xlsx');
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
                            return  ["id" => $row->id, "text" => $row->first_name ];
                        });
    }

    public function getItemDropdown(Request $request)
    {
        $query = $request->input('q');
        return Item::where('title','like', '%' .  $query. '%') 
                        ->orWhere('item_ref', 'like', '%' .  $query. '%')
                        ->orWhere('isbn', 'like', '%' .  $query. '%')
                        ->orWhere('call_number', 'like', '%' .  $query. '%')
                        ->where('is_active', 1)
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => $row->title];
                        });
    }
}
