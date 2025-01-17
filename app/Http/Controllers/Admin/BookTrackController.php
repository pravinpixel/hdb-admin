<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Checkout;
use App\Models\User;
use App\Models\Issue;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Laracasts\Flash\Flash;
use Auth;
class BookTrackController extends Controller
{
    public function index()
    {
        return view('admin.book-track.index');
    }
    public function datatable( Request $request)
    {
        if ($request->ajax() == true) {
            $start_date = $request->start_date ?? Carbon::now()->subDays(6);
            $end_date = $request->end_date ?? now();
            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = Carbon::parse($end_date)->endOfDay();
            $dataDb = Checkout::query()->with('item','user','due');
            $dataDb->whereBetween('date', [$start_date, $end_date]);
            $dataDb->whereIn('status', ['taken', 'returned']);
            $dataDb->orderBy('date', 'desc');
            return DataTables::eloquent($dataDb)
            ->editColumn('date', function($dataDb) {
                return Carbon::parse($dataDb->date)->format('d-m-Y');
            })
            ->editColumn('checkout_date', function($dataDb) {
                return isset($dataDb->checkout_date)? Carbon::parse($dataDb->date)->format('d-m-Y') : '';
            })
            ->editColumn('date_of_return', function($dataDb) {
                return Carbon::parse($dataDb->date_of_return)->format('d-m-Y');
            })
            ->editColumn('status', function($dataDb) {
              if(isset($dataDb->due) && $dataDb->due->count() >0){
                 $count =$dataDb->due->count();
                }
               if($dataDb->status=='taken'){
                return '<span class="label label-warning label-sm">Taken  '.(isset($count)?$count:'').'</span>';
               }elseif($dataDb->status=='returned'){
                return '<span class="label label-success label-sm">Returned</span>';
               }else{
               return '<span class="label label-primary label-sm">Pending</span>';
               }
            })
            ->addColumn('action', function ($dataDb) {
                 if($dataDb->status=='taken'){
                  return '
                  <a href="' . route('book-track.show', $dataDb->id) . '" id="tooltip" title="View" disaled><span class="label label-primary label-sm"><i class="fa fa-eye"></i></span></a>
                  <a href="' . route('book-track.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                   <a href="#"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
                 }else{
                    return '
                    <a href="' . route('book-track.show', $dataDb->id) . '" id="tooltip" title="View" disaled><span class="label label-primary label-sm"><i class="fa fa-eye"></i></span></a>
                    <a href="' . route('book-track.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                     <a href="#" data-message="' . trans('auth.book_confirmation') . '" data-href="' . route('book-track.destroy', $dataDb->id) . '" id="tooltip"  data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete" ><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
                }
            })
            ->rawColumns(['status','action'])
            ->make(true);
           
        }
    }
    public function show($id)
    {
        $item = Checkout::find($id);
        return view('admin.book-track.view', compact('item'));
    }
    public function edit($id)
    {
        $item = Checkout::find($id);
        return view('admin.book-track.edit', compact('item'));
    }
    public function update(Request $request, $id)
    {

        $request->validate([
            'checkin_date'  => ['required', 'date'],
            'due_date'  => ['required','date','after_or_equal:checkout_date'],
            'checkout_date'      =>  ['nullable','required_if:status,returned', 'date', 'after_or_equal:checkin_date'],
        ]);

        $item = Checkout::find($id);
        if(isset($request->checkin_date)){
            $item->date = $request->checkin_date;
        }
        if(isset($request->checkout_date) && !empty($request->checkout_date)){
            $item->checkout_date = $request->checkout_date;
            $item->status ='returned';
        }
        if(isset($request->due_date)){
            $item->date_of_return = $request->due_date; 
        }
        $item->save();
        if(isset($request->due_date) && $item->status !="returned" && $request->due_date !=$item->date_of_return){
        $issue = new Issue();
        $issue->checkout_id = $item->id;
        $issue->item_id =  $item->item_id;
        $issue->issue_date = Carbon::now();
        $issue->approve_request_id =1;
        $issue->approved_by = 1;
        $issue->received_by =1;
        $issue->date_of_return = $request->due_date;
        $issue->issue_status =1;
        $issue->save();
        }
        Flash::success( __('auth.update_book'));
        return view('admin.book-track.index', compact('item'));
    }
    public function destroy($id)
    {

        $data = Checkout::find($id);

        if (empty($data)) {
           Flash::error( __('global.not_found'));

            return redirect()->route('book-track.index');
        }

        $data->delete();

       Flash::success( __('auth.delete_book'));

       return redirect()->route('book-track.index');

    }
}
