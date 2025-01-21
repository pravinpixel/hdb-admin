<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Checkout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class StaffWiseViewHistoryExport implements FromCollection, WithMapping, WithHeadings
{
    public $member_id;
    public $item_id;
    public $start_date;
    public $end_date;

    public function __construct($request)
    {
        $this->member_id = $request->member_id;
        $this->item_id = $request->item_id;
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $dataDb = User::with(['checkouts', 'roles'])->where('role',7);

        if ($this->member_id) {
            $dataDb->where('id', $this->member_id);
        }
        $starts_date = $this->start_date ?? now();
        $ends_date = $this->end_date ?? now();
        $start_date = Carbon::parse($starts_date)->format('Y-m-d');
        $end_date = Carbon::parse($ends_date)->format('Y-m-d');
        $dataDb->whereBetween('created_at', [$start_date, $end_date]);
        return $dataDb->with(['checkouts', 'roles'])
            ->get();
    }

    public function map($dataDb): array
    {
        return [
            $dataDb->member_id,
            $dataDb->first_name,
            $dataDb->designation ?? '',
            $dataDb->checkouts()->count(),
            $dataDb->created_at->format('d-m-Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'Staff ID',
            'Staff Name',
            'Designation',
            'Total Item Taken',
            'Created At'
        ];
    }
}
