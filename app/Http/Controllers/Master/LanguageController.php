<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLanguageRequest;
use App\Http\Requests\UpdateLanguageRequest;
use App\Models\Language;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('master.language.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.language.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLanguageRequest $request)
    {
        $type = new Language();
        $type->language = $request->language;
        if ($type->save()) {
            Flash::success(__('global.created'));
            return redirect()->route('language.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('language.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('master.language.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $result = Language::find($id);
        if (!empty($result)) {
            return view('master.language.edit', compact('result'));
        }
        Flash::error(__('global.something'));
        return redirect()->route('language.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'language' => ['required', Rule::unique('languages')->ignore($id)->whereNull('deleted_at')]
        ]);
        $type = Language::find($id);
        $type->language = $request->language;
        if ($type->save()) {
            Flash::success(__('global.updated'));
            return redirect()->route('language.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('language.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = Language::with('item')->find($id);

        if (empty($type)) {
            Flash::error(__('global.not_found'));

            return redirect()->route('language.index');
        }
        if ($type->item()->exists()) {
            Flash::error(__('global.can_not_delete'));
            return redirect()->route('language.index');
        }

        $type->delete();
        Flash::success(__('global.deleted'));
        return redirect()->route('language.index');
    }

    public function datatable(Request $request)
    {
        if ($request->ajax() == true) {
            $dataDb =  Language::with('item');
            return DataTables::eloquent($dataDb)
                ->editColumn('created_at', function ($dataDb) {
                    return Carbon::parse($dataDb->created_at)->format('d-m-Y');
                })
                ->editColumn('updated_at', function ($dataDb) {
                    return Carbon::parse($dataDb->updated_at)->format('d-m-Y');
                })
                ->addColumn('status', function ($dataDb) {

                    if ($dataDb->is_active == 1) {
                        $message = $dataDb->item()->exists() ? trans('global.deactivate_subheading_master', ['name' => $dataDb->language]) : trans('global.deactivate_subheading', ['name' => $dataDb->language]);
                        return '<a href="#" data-message="' . $message . '" data-href="' . route('language.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('global.activate_subheading') . '" data-title-modal="' . trans('global.activate_subheading') . '" data-toggle="modal" data-target="#delete" title="' . trans('global.deactivate') . '"><span class="label label-success label-sm">' . trans('auth.index_active_link') . '</span></a>';
                    }
                    return '<a href="#" data-message="' . trans('global.activate_subheading', ['name' => $dataDb->language]) . '" data-href="' . route('language.status', $dataDb->id) . '" id="tooltip" data-method="PUT" data-title="' . trans('global.activate_subheading') . '" data-title-modal="' . trans('global.activate_subheading') . '" data-toggle="modal" data-target="#delete" title="' . trans('global.activate') . '"><span class="label label-danger label-sm">' . trans('auth.index_inactive_link') . '</span></a>';
                })
                ->addColumn('action', function ($dataDb) {
                    return '<a href="' . route('language.edit', $dataDb->id) . '" id="tooltip" title="Edit"><span class="label label-warning label-sm"><i class="fa fa-edit"></i></span></a>
                        <a href="#" data-message="' . trans('global.delete_confirmation', ['name' => $dataDb->material_code]) . '" data-href="' . route('language.destroy', $dataDb->id) . '" id="tooltip" data-method="DELETE" data-title="Delete" data-title-modal="' . trans('auth.delete_confirmation_heading') . '" data-toggle="modal" data-target="#delete"><span class="label label-danger label-sm"><i class="fa fa-trash-o"></i></span></a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }


    public function status($id)
    {
        $result = Language::find($id);
        $result->is_active = ($result->is_active == 1) ? 0 : 1;
        $result->update();
        if ($result) {
            Flash::success(__('global.status_updated'));
            return redirect()->route('language.index');
        }
        Flash::error(__('global.something'));
        return redirect()->route('language.index');
    }

    public function getDropdown(Request $request)
    {
        $query = $request->input('q');
        return Language::where('language', 'like', '%' .  $query . '%')
            ->where('is_active', 1)
            ->limit(25)
            ->get()
            ->map(function ($row) {
                return  ["id" => $row->id, "text" => $row->language];
            });
    }
}
