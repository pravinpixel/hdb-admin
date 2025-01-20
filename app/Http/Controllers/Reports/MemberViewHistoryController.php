<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use App\Exports\MemberViewHistoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class MemberViewHistoryController extends Controller
{
    public function index(Request $request) {
      
    	$start_date = $request->start_date ?? Carbon::now()->subDays(6);
        $end_date = $request->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        $members = User::with(['checkouts','roles'])
                // ->whereHas('checkouts', function($q) use ($start_date, $end_date) {
                //     $q->whereBetween('created_at', [$start_date, $end_date]);
                // })
                ->when($request->start_date , function($q) use ($start_date, $end_date) {
                    $q->whereBetween('created_at', [$start_date, $end_date]);
                })
                ->when($request->member , function($q) use($request) {
                    $q->where('id', $request->member );
                })
                ->where('role',7)
                ->paginate(20);
        $member_name = 'Select Member';
        $member_id = $request->member ?? null;
        $user = User::find($member_id);
        return view('admin.reports.member-view-history.index', compact('members','start_date','end_date','user'));
    }

    public function export(Request $request) 
    {
        return Excel::download(new MemberViewHistoryExport($request), 'member-view-history.xlsx');
    }

    public function getMemberDropdown(Request $request)
    {
        $query = $request->input('q');
        return User::where('first_name','like', '%' .  $query. '%')
                        ->where('role',7)
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => $row->first_name];
                        });
    }
}
