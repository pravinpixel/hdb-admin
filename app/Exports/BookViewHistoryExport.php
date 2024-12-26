<?php

namespace App\Exports;

use App\Models\Checkout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookViewHistoryExport implements FromCollection, WithMapping, WithHeadings
{
    public $member_id;
    public $item_id;
    
    public function __construct($request)
    {
        $this->member_id = $request->member_id;
        $this->item_id = $request->item_id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $dataDb = Checkout::query();

        if($this->member_id) {
            $dataDb->where('checkout_by', $this->member_id);
        } 

        if($this->item_id) {
            $dataDb->where('item_id', $this->item_id);
        }

        return $dataDb->with(['user','item'])
                ->get();
    }

    public function map($checkout) : array {
        return [
            $checkout->item->item_id,
            $checkout->item->item_name,
            $checkout->user->first_name,
            $checkout->date_of_return
        ];
    }

    public function headings() : array {
        return [
           'Item ID',
           'Item Name',
           'Member Name',
           'Date of Return'
        ];
    }
}
