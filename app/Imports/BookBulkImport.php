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
use Illuminate\Validation\Rule;
class BookBulkImport implements ToCollection, WithHeadingRow
{
    public $collection;
    public $errors = [];

/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Import Staff Data from Excel File
     * @param Collection $rows Excel rows collection
     * @return void
     */
/******  22990f5a-01db-4017-8429-abd14be683e9  *******/
    public function collection(Collection $rows)
    {
        $this->validateRow($rows);
        if (!count($this->errors)) {
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
            // $ins['location'] = $row['location'];
            $ins['isbn'] = $row['isbn'];
            $ins['call_number'] = $row['call_number'];
            $ins['status'] = $row['status'] == 'active' ? 1 : 0;
            $ins['created_by'] = Auth::id();
            // $ins['due_period']= $row['due_period'];
                Item::updateOrCreate(['item_ref' => $row['rfid']], $ins);
            }
        }
    }

    private function validateRow($rows)
    {
        foreach ($rows as $row) {
            // $error = $this->validateRow($row);
            $row = $row->toArray();
            $row['barcode'] = $row['accession_barcode_number'] ?? null;
            $validationRules = [
                'rfid'         => 'required',
                'title'        => 'required',
                'author'         => 'required',
                'isbn' => 'required|min:1|max:13',
                'subject'        => 'required',
                'language'=>'required',
                'call_number' => 'nullable|integer|digits:10',
                'barcode' => ['nullable']
            ];
    
            $validator = Validator::make($row, $validationRules);
    
            if ($validator->fails()) {
                $validatorError = $validator->errors()->messages();
                if (isset($validatorError['rfid'])) 
                    $this->errors[] = $row['rfid'].' '.$validatorError['rfid'][0];
                if (isset($validatorError['title'])) 
                    $this->errors[] = $row['title'].' '.$validatorError['title'][0];
                if (isset($validatorError['author'])) 
                    $this->errors[] = $row['author'].' '.$validatorError['author'][0];
                if (isset($validatorError['isbn'])) 
                    $this->errors[] = $row['isbn'].' '.$validatorError['isbn'][0];
                if (isset($validatorError['subject'])) 
                    $this->errors[] = $row['subject'].' '.$validatorError['subject'][0];
                if (isset($validatorError['language'])) 
                    $this->errors[] = $row['language'].' '.$validatorError['language'][0];
                if (isset($validatorError['call_number'])) 
                    $this->errors[] = $row['call_number'].' '.$validatorError['call_number'][0];
                if (isset($validatorError['barcode'])) 
                    $this->errors[] = $row['barcode'].' '.$validatorError['barcode'][0];
            }
        }
    }
}
