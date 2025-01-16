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
class StaffBulkImport implements ToCollection, WithHeadingRow
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
            $ins['member_id']=$row['staff_no'];
            $ins['first_name']=$row['name'];
            $ins['designation']=$row['designation'] ?? NULL;
            $ins['group']=$row['orgngroup']?? NULL;
            $ins['email']=$row['email_address'];
            $ins['is_active']=1;
            $ins['role']=7;
            $ins['created_by']='admin';
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
