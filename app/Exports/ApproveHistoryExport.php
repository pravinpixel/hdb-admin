<?php

namespace App\Exports;

use App\Models\ApproveRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApproveHistoryExport implements FromCollection, WithMapping, WithHeadings
{

    public $status;
    public $memeber;
    public function __construct($request)
    {
        $this->status = $request->status;
        $this->member = $request->member;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $where = [];
        if($this->member && $this->status) {
            $where = [ $this->status => $this->member];
        }
        $dataDb = ApproveRequest::where($where)
                ->with('item','user','rejectedBy','approvedBy')
                ->get();
        return $dataDb;
    }
 
    public function map($approve_request) : array {
        return [
            $approve_request->item->item_id,
            $approve_request->item->item_name,
            $approve_request->user->first_name ?? '',
            $approve_request->rejectedBy->first_name ?? '',
            $approve_request->approvedBy->first_name ?? '',
        ];
    }

    public function headings() : array {
        return [
           'Item ID',
           'Item Name',
           'Requested By',
           'Rejected By',
           'Approved By'
        ] ;
    }
}
