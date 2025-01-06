<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\Config;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.item.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $config = Config::where('type', 'item')->get()->first();
        $item_ref = $config->item_prefix.'-'.$config->item_number;
        return view('master.item.create', compact('item_ref'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateItemRequest $request)
    {

        $config = Config::where('type', 'item')->get()->first();
        $item = new Item();
        if(isset($request->cover_image)){
             $path = $this->storeImage($request);
            $item->cover_image =  $path;
        }
        $item->title = $request->title;
        $item->subject = $request->subject;
        $item->item_ref = $config->item_prefix.'-'.$config->item_number;
        $item->barcode = $request->barcode;
        $item->rfid = $config->item_prefix.'-'.$config->item_number;
        $item->language_id = $request->language_id;
        $item->author = $request->author;
        $item->location = $request->location;
        $item->isbn = $request->isbn;
        $item->call_number = $request->call_number;
        $item->status = ($request->status=='on') ? 1 : 0;
        $item->created_by = Auth::id();
        $item->due_period = $request->due_period;
        if($item->save()) {
            $this->updateConfigItemNumber();
            Flash::success(__('global.inserted'));
            return redirect()->route('item.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('item.index');
    }

    public function storeImage(Request $request)
    {
        if($request->file('cover_image') != null) {
            return $request->file('cover_image')->storePublicly('item-images', 'public_uploads');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::with(['user','language'])
                    ->where('id' , $id)
                    ->where('status' , 1)
                    ->first();
        if( empty( $item ) ) {
            return response(['status' => false, 'msg' => trans('global.not_found')], 404);
        }
        $data['item'] = $item;
        $data['approval_request'] =  null;
        if( !empty( $item ) ) {
            return view('employee.item-detail', compact('data'));
        } 
        return response(['status' => false, 'msg' => trans('global.not_found')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = Item::find($id);
        if( !empty( $result ) ) {
            $config = Config::where('type', 'item')->get()->first();
            $item_ref = $result->item_ref;
            return view('master.item.edit', compact('result','item_ref'));
        } 
        Flash::error(__('global.something'));
        return redirect()->route('item.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::find($id);
        if( empty( $item ) ) {
            Flash::error(__('global.something'));
            return redirect()->route('item.index');
        }
        if(!empty($request->cover_image)) {
            $file_path = public_path().'/uploads/'.$item->cover_image;
            if(file_exists($file_path)) {
                unlink($file_path);
            }
            $path = $this->storeImage($request);
            $item->cover_image = $path;
        }
        $item->title = $request->title;
        $item->item_ref = $request->item_ref;
        $item->barcode = $request->barcode;
        $item->rfid = $request->item_ref;
        $item->language_id = $request->language_id;
        $item->subject = $request->subject;
        $item->location = $request->location;
        $item->isbn = $request->isbn;
        $item->call_number = $request->call_number;
        $item->author = $request->author;
        $item->status = ($request->status=='on') ? 1 : 0;
        $item->due_period = $request->due_period;
        if($item->save()) {
             Flash::success(__('global.updated'));
             return redirect()->route('item.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('item.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Item::find($id);
        if (empty($item)) {
             Flash::error( __('global.not_found'));
             return redirect()->route('item.index');
        }
        // if( $item->approveRequestByUser()->exists() ) {
        //     Flash::error( __('global.can_not_delete'));
        //     return redirect()->route('item.index');
        // }
        $item->delete();
        Flash::success( __('global.deleted'));
        return redirect()->route('item.index');
    }

    public function datatable(Request $request) 
    {
      if ($request->ajax() == true) {
          $dataDb =  Item::with('user','language');
          return DataTables::eloquent($dataDb)
          ->editColumn('created_at', function($dataDb){
               return Carbon::parse($dataDb->created_at)->format('d-m-Y');
          })
          ->editColumn('updated_at', function($dataDb){
               return Carbon::parse($dataDb->updated_at)->format('d-m-Y');
          })
            ->addColumn('image', function ($dataDb) { 
                $url = asset('uploads/'.$dataDb->cover_image);
                return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center"  />'; 
            })
            ->addColumn('approval', function($dataDb) {
                return ($dataDb->is_need_approval == 1) ?  
                            '<div><span class="label label-success label-sm"> Yes </span></div>' :      
                            '<div><span class="label label-danger label-sm"> No </span></div>';
            })

          ->addColumn('status', function ($dataDb) {
            if ($dataDb->status == 1) {
                $message =trans('global.deactivate_subheading', ['name' => $dataDb->item_name]);
                return '<a href="#" data-message="' .  $message . '" data-href="' . route('item.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('global.activate_subheading') . '" data-title-modal="' . trans('global.activate_subheading') . '" data-toggle="modal" data-target="#delete" title="' . trans('global.deactivate') . '"><span class="label label-success label-sm">' . trans('auth.index_active_link') . '</span></a>';
           }
           return '<a href="#" data-message="' . trans('global.activate_subheading', ['name' => $dataDb->item_name]) . '" data-href="' . route('item.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('global.activate_subheading') . '" data-title-modal="' . trans('global.activate_subheading') . '" data-toggle="modal" data-target="#delete" title="' . trans('global.activate') . '"><span class="label label-danger label-sm">' . trans('auth.index_inactive_link') . '</span></a>';
          })
          ->addColumn('action', function ($dataDb) {
               return '<a href="' . route('item.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                         <a href="#" data-message="' . trans('global.delete_confirmation', ['name' => $dataDb->item_name]) . '" data-href="' . route('item.destroy', $dataDb->id) . '" id="tooltip" data-method="DELETE" data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';     
          })
          ->rawColumns(['status','action','image','approval'])
          ->make(true);
          }
    }

    public function status($id)
    {
        $result = Item::find($id);
        $result->status = ($result->status == 1) ? 0 : 1;
        $result->update();
        if( $result ) {
            Flash::success(__('global.status_updated'));
            return redirect()->route('item.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('item.index');
    }

    public function updateConfigItemNumber()
    {
        $config = Config::where('type', 'item')->get()->first();
        $config->item_number =  $config->item_number + 1;
        Log::info('Increment item number');
        return $config->save();
    }

    public function getItem(Request $request)
    {
        $item_id = $request->input('item');
        $items = Item::with(['category','subcategory','genre','type','user','approveRequestByUser'])
                    ->where('is_active' , 1)
                    ->where('item_name' , 'like', '%'.$item_id.'%')
                    ->orwhere('item_id' ,'like', '%'.$item_id.'%')
                    ->limit(25)
                    ->get();
        if( empty( $items ) ) {
            return response(['status' => false, 'msg' => trans('global.not_found')], 404);
        }
        $datas = [];
        foreach($items as $item) {
            if($item->approveRequestByUser) {
                $approval_request = $item->approveRequestByUser()->where([
                    'requested_by' => Sentinel::getUser()->id
                ])->orderBy('id','desc')->first();
            }
            if($item->is_issued == 1){
                $approval = '<div class=""><div class="badge badge-info">N/A</div></div>';
            } else if($approval_request) {
                if($approval_request->approve_status == 2) {
                    $approval = '<a href="#" id="tooltip" data-title="request acceptted" class="btn btn-success btn-sm"> Request Accepted </a>';
                }
                elseif($approval_request->approve_status == 1) {
                    $approval =  '<a href="#" id="tooltip" data-title="request send" class="btn btn-warning btn-sm"> Request Sent </a>';
                }
                elseif($approval_request->approve_status == 3) {
                    $approval = '<a href="#" class="approve-request" data-message="'.trans('employee.are_you_want_to_process', ['name' => $item->item_name]).'" data-href="'.route('employee.approve-request', $item->id).'" id="tooltip" data-method="PUT" data-title="send approve request" data-toggle="modal" data-target="#delete"> <span class="btn btn-info btn-sm"> Send Approval Request </span></a>';
                }
            } else {
                $approval = '<a href="#" class="approve-request" data-message="'.trans('employee.are_you_want_to_process', ['name' => $item->item_name]).'" data-href="'.route('employee.approve-request', $item->id).'" id="tooltip" data-method="PUT" data-title="send approve request" data-toggle="modal" data-target="#delete"> <span class="btn btn-info btn-sm"> Send Approval Request </span></a>';
            }
            if($item->user) {
                $user = ($item->user->id == Sentinel::getUser()->id) ? 'You' : $item->user->full_name;
                $checkout = '<div class="text-left"><div class="badge badge-danger"> Issued </div> <div class="badge badge-danger"> '.  $user.' </div></div>';
            } else {
                $checkout = '<div class="text-left"><div class="badge badge-success">Available</div></div>';
            }
            
            $data['approval_request'] = $approval ?? null;
            $data['item']             = $item;
            $data['checkout']         = $checkout ;
            $data['view']             = '<a href="#" class="view-item"  data-item-id="'.$item->id.'"><div class="badge badge-info"> View</div></a>' ;
            $datas[] = $data;
        }
        if( !empty( $datas ) ) {
            return response(['status' => true, 'data' => $datas]);
        } 
        return response(['status' => false, 'msg' => trans('global.not_found')]);
    }

    public function getDropdown(Request $request) 
    {
        $query = $request->input('q');
        return Item::where('item_name','like', '%' .  $query. '%') 
                        ->orWhere('item_id', 'like', '%' .  $query. '%')
                        ->where('is_active', 1)
                        ->limit(25)
                        ->get()
                        ->map(function($row) {
                            return  ["id" => $row->id, "text" => "$row->item_name [{$row->item_id}]"];
                        });
    }
}
