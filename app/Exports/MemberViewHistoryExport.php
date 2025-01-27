<?php
namespace App\Exports;

use App\Models\User;
use App\Models\Checkout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class MemberViewHistoryExport implements FromCollection, WithMapping, WithHeadings
{
    public $member_id;
    public $item_id;
    public $start_date;
    public $end_date;

    public function __construct($request)
    {
        $this->member_id = $request->member;
        $this->item_id = $request->item;
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dataDb = Checkout::with(['user','item']);
        if($this->member_id) {
            $dataDb->where('checkout_by', $this->member_id);
        }
        if($this->item_id) {
            $dataDb->where('item_id', $this->item_id);
        }
        $starts_date = $this->start_date ?? now();
        $ends_date = $this->end_date ?? now();
        $start_date = Carbon::parse($starts_date)->format('Y-m-d');
        $end_date = Carbon::parse($ends_date)->format('Y-m-d');
        $dataDb->whereBetween('date', [$start_date, $end_date]);
        return $dataDb->with(['user','item'])
                ->get();
    }

    public function map($dataDb) : array {
        $checkout_date = Carbon::parse($dataDb->date)->format('d-m-Y');
        $checkin_date = $dataDb->checkout_date ? Carbon::parse($dataDb->checkout_date)->format('d-m-Y') : null;
        return [
            $dataDb->user->member_id,
            $dataDb->user->first_name,
            $dataDb->item ? $dataDb->item->title : '',
            $checkout_date,
            $checkin_date
        ];
    }

    public function headings() : array {
        return [
           'Staff ID',
           'Staff Name',
           'Book Name',
           'Date of CheckOut',
           'Date of CheckIn'
        ];
    }
}
