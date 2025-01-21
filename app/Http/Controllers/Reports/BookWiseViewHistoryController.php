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
use Laracasts\Flash\Flash;
class BookWiseViewHistoryController extends Controller
{
    public function index(Request $request)
    {

        $start_date = $request->start_date ?? Carbon::now()->subDays(6);
        $end_date = $request->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        $items = Item::with(['language', 'checkouts'])
            ->when($request->item, function ($q) use ($request) {
                $q->where('id', $request->item);
            })
            ->whereHas('checkouts', function ($q) use ($start_date, $end_date) {
                $q->when(!Sentinel::inRole('admin'), function ($q) {
                    $q->where('checkout_by', Sentinel::getUser()->id);
                })->whereBetween('date', [$start_date, $end_date]);
            })->when($start_date && $end_date, function ($q) use($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date]);
            })->paginate(10);
        $item_id = $request->item ?? null;
        $item = Item::find($item_id);
        return view('admin.reports.book-view-history.index', compact('items', 'start_date', 'end_date', 'item'));
    }

    public function datatable(Request $request)
    {
        if ($request->ajax()) {
            $start_date = $request->start_date ?? null;
            $end_date = $request->end_date ?? null;
            if (isset($start_date) && isset($end_date)) {
                $start_date_obj = \Carbon\Carbon::createFromFormat('d-m-Y', $start_date);
                $end_date_obj = \Carbon\Carbon::createFromFormat('d-m-Y', $end_date);
                if ($end_date_obj->lt($start_date_obj)) {
                    return response(['status' => false, 'msg' => trans('global.date_invalid')], 404);
                }
              
            }
            if (isset($start_date)) {
                $start_date = Carbon::parse($start_date)->startOfDay();
                $end_date = Carbon::parse($end_date)->endOfDay();
            }

            $item_id = $request->item_id ?? null;

            $query = Item::with(['language', 'checkouts' => function ($q) use ($start_date, $end_date) {
                $q->when(!Sentinel::inRole('admin'), function ($q) {
                    $q->where('checkout_by', Sentinel::getUser()->id);
                });
            }])
                ->when($item_id, function ($q) use ($item_id) {
                    $q->where('id', $item_id);
                })->when(isset($start_date) && isset($end_date), function ($q) use ($start_date, $end_date) {
                    $q->whereBetween('created_at', [$start_date, $end_date]);
                });
            return DataTables::eloquent($query)
                ->addColumn('title', function ($item) {
                    return $item->title;
                })
                ->addColumn('call_number', function ($item) {
                    return $item->call_number ?? 'N/A';
                })
                ->addColumn('isbn', function ($item) {
                    return $item->isbn ?? 'N/A';
                })
                ->addColumn('total_member_taken', function ($item) {
                    return $item->checkouts->count();
                })
                ->addColumn('created_at', function ($item) {
                    return $item->created_at->format('d-m-Y');
                })
                ->rawColumns(['title', 'call_number', 'isbn', 'total_member_taken', 'created_at'])
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
        return User::where('first_name', 'like', '%' .  $query . '%')
            ->when(!Sentinel::inRole('admin'), function ($q) {
                $q->where('id', Sentinel::getUser()->id);
            })
            ->limit(25)
            ->get()
            ->map(function ($row) {
                return  ["id" => $row->id, "text" => $row->first_name];
            });
    }

    public function getItemDropdown(Request $request)
    {
        $query = $request->input('q');
        return Item::where('title', 'like', '%' .  $query . '%')
            ->orWhere('item_ref', 'like', '%' .  $query . '%')
            ->orWhere('isbn', 'like', '%' .  $query . '%')
            ->orWhere('call_number', 'like', '%' .  $query . '%')
            ->where('is_active', 1)
            ->limit(25)
            ->get()
            ->map(function ($row) {
                return  ["id" => $row->id, "text" => $row->title];
            });
    }
}
