<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Events\AfterImport;
use Carbon\Carbon;
use App\Models\Item;
use App\Models\Language;
use Auth;
class BookBulkImport implements ToCollection, WithHeadingRow
{
    public $collection;

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Import Staff Data from Excel File
     * @param Collection $rows Excel rows collection
     * @return void
     */
/******  22990f5a-01db-4017-8429-abd14be683e9  *******/
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $language=Language::where('language','like',"%{$row['language']}%")->first();
            if(empty($language)){
                $language_data = new Language();
                $language_data->language = $row['language'];
                $language_data->save();
                $language_id=$language_data->id;
            }else{
             $language_id=$language->id;
            }
        $ins['title'] = $row['title'];
        $ins['subject'] = $row['subject'];
        $ins['item_ref'] = $row['rfid'];
        $ins['barcode'] = $row['accession_barcode_number'];
        $ins['rfid'] = $row['rfid'];
        $ins['language_id'] = $language_id;
        $ins['author'] = $row['author'];
        $ins['location'] = $row['location'];
        $ins['isbn'] = $row['isbn'];
        $ins['call_number'] = $row['call_number'];
        $ins['status'] = $row['status'] ?? 1;
        $ins['created_by'] = Auth::id();
        $ins['due_period']= $row['due_period'];
             Item::updateOrCreate(['item_ref' => $row['rfid']], $ins);
        }
    }


}
