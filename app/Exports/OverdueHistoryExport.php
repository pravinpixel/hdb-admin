<?php

namespace App\Exports;

use App\Models\Checkout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;


class OverdueHistoryExport implements FromCollection, WithMapping, WithHeadings
{

    public $member_id;
    public $start_date;
    public $end_date;
    public function __construct($request)
    {
        $this->member_id = $request->member_id;
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       
        $dataDb = Checkout::query();

        $start_date = $this->start_date ?? now();
        $end_date = $this->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('d-m-Y');
        $end_date = Carbon::parse($end_date)->format('d-m-Y');

        if($this->member_id) {
            $dataDb->where('checkout_by', $this->member_id);
        }
        return $dataDb->where('return_status',false)
                ->whereBetween('date_of_return', [$start_date, $end_date])
                ->with(['user','item'])
                ->get();
    }
   public function map($checkout) : array {
        $current_date = strtotime(Date('Y-m-d'));
        $checkout_date = strtotime($checkout->date_of_return);
        $day_diff = ( $checkout_date - $current_date ) / 86400 ;
        return [
            $checkout->created_at,
            $checkout->item->item_id,
            $checkout->item->item_name,
            $checkout->user->first_name,
            $day_diff.' days',
            $checkout->date_of_return
        ];
    }

    public function headings() : array {
        return [
           'Created At',
           'Item ID',
           'Item Name',
           'Member Name',
           'Overdue Days',
           'Date of Return'
        ];
    }
}
