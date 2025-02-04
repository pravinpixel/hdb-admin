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
use App\Models\User;
use App\Models\Role;
use Laracasts\Flash\Flash;
class StaffBulkImport implements ToCollection, WithHeadingRow
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
                $ins['member_id']=$row['staff_no'];
                $ins['first_name']=$row['name'];
                $ins['designation']=$row['designation'] ?? NULL;
                $ins['group']=$row['orgngroup']?? NULL;
                $ins['email']=$row['email_address'];
                $ins['is_active']=1;
                $ins['role']=7;
                $ins['created_by']='admin';
                $ins['location'] = $row['location'];
                $ins['collection'] = $row['collection'];
                $ins['imprint'] = $row['imprint'];
                $exitUser=User::where('member_id' ,$row['staff_no'])->first();
                $user=User::updateOrCreate(['member_id' => $row['staff_no']], $ins);
                $role = Role::find(7);
                if(!$exitUser){
                    $role->users()
                    ->attach($user);
                }
            }
        }
    }

    private function validateRow($rows)
    {
        // dd($rows);
        foreach ($rows as $row) {
            // $error = $this->validateRow($row);
            $validationRules = [
                'staff_no' => 'required|regex:/^[A-Za-z][0-9]{5}$/',
                'name' => 'required|regex:/(^[A-Za-z ]+$)+/',
                'email_address' => 'required|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'designation' => 'required',
                'orgngroup' => 'required',
                'location'       => 'required',
                'collection'       => 'required',
                'imprint'       => 'required',
            ];
    
            $validator = Validator::make($row->toArray(), $validationRules, [
                'staff_no.regex' => 'Staff No must be 1 alphabet followed by 5 numbers',
                'name.regex' => 'Name should only contain alphabets and spaces.',
            ]);
    
            if ($validator->fails()) {
                $validatorError = $validator->errors()->messages();
                if (isset($validatorError['staff_no'])) 
                    $this->errors[] = ($row['staff_no'] ?? '').' '.$validatorError['staff_no'][0];
                if (isset($validatorError['name'])) 
                    $this->errors[] = ($row['name'] ?? '').' '.$validatorError['name'][0];
                if (isset($validatorError['email_address'])) 
                    $this->errors[] = ($row['email_address'] ?? '').' '.$validatorError['email_address'][0];
                if (isset($validatorError['designation'])) 
                    $this->errors[] = ($row['designation'] ?? '').' '.$validatorError['designation'][0];
                if (isset($validatorError['orgngroup'])) 
                    $this->errors[] = ($row['orgngroup'] ?? '').' '.$validatorError['orgngroup'][0];
                if (isset($validatorError['location'])) 
                $this->errors[] = ($row['location'] ?? '').' '.$validatorError['location'][0];
                if (isset($validatorError['collection'])) 
                $this->errors[] = ($row['collection'] ?? '').' '.$validatorError['collection'][0];
                if (isset($validatorError['imprint'])) 
                $this->errors[] = ($row['imprint'] ?? '').' '.$validatorError['imprint'][0];
            }
        }
    }
}
