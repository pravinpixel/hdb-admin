<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Laracasts\Flash\Flash;
use App\Imports\StaffBulkImport;
use App\Imports\BookBulkImport;
use Maatwebsite\Excel\Facades\Excel;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('report.index');
    }

    public function StaffUpdate(Request $request)
    {
        $request->validate([
            'file' => [
              'required',
              'file',
              'mimes:csv,xlsx,xls',
              'max:2048' 
          ] 
           
        ]);  
          $exampleImport = new StaffBulkImport;
          $excel_import=Excel::import($exampleImport, $request->file);
          if($excel_import)
          {
            Flash::success( __('auth.import_successful'));
            return redirect()->route('bulk-upload.index');
          }
          else
          {
            Flash::success( __('auth.import_unsuccessful'));
            return redirect()->route('bulk-upload.index');
          } 
          return redirect()->route('bulk-upload.index');
    }

    public function BookUpdate(Request $request)
    {
        $request->validate([
            'books' => [
              'required',
              'file',
              'mimes:csv,xlsx,xls',
              'max:2048' 
          ]      
        ],[
          'books.file'=>'The file must be a file of type: csv, xlsx, xls.',
          'books.mimes'=>'The file must be a file of type: csv, xlsx, xls.'
        ]);  
          $exampleImport = new BookBulkImport;
          $excel_import=Excel::import($exampleImport, $request->books);
          if($excel_import)
          {
            Flash::success( __('auth.import_successful'));
            return redirect()->route('bulk-upload.index');
          }
          else
          {
            Flash::success( __('auth.import_unsuccessful'));
            return redirect()->route('bulk-upload.index');
          } 
          return redirect()->route('bulk-upload.index');
    }

    
}
