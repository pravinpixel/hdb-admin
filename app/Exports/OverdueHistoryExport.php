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

        $starts_date = $this->start_date ?? now();
        $ends_date = $this->end_date ?? now();
        $start_date = Carbon::parse($starts_date)->format('Y-m-d');
        $end_date = Carbon::parse($ends_date)->format('Y-m-d');

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
            $checkout->item->item_ref,
            $checkout->item->isbn,
            $checkout->user->first_name,
            $checkout->checkout_date,
            $checkout->date,
            $day_diff.' days',
            Carbon::parse($checkout->created_at)->format('Y-m-d'),

        ];
    }

    public function headings() : array {
        return [
           'Book Title ID',
           'ISBN',
           'Staff Name',
           'Checkout Date',
           'Checkin Date',
           'Overdue Days',
           'Created At',
        ];
    }
}
