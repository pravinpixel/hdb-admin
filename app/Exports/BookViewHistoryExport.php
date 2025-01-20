<?php

namespace App\Exports;

use App\Models\Checkout;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\Item;
use Carbon\Carbon;

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
        
        $start_date = $this->start_date ?? Carbon::now()->subDays(6);
        $end_date = $this->end_date ?? now();
        $start_date = Carbon::parse($start_date)->format('Y-m-d');
        $end_date = Carbon::parse($end_date)->format('Y-m-d');
        $item_id = $this->item_id ?? null;
        $items = Item::with(['language','checkouts'])
                ->when($this->item_id , function($q) use($item_id) {
                    $q->where('id', $this->item_id );
                })
                ->whereHas('checkouts', function($q) use ($start_date, $end_date) {
                    $q->when(!Sentinel::inRole('admin'), function($q) {
                        $q->where('checkout_by', Sentinel::getUser()->id);
                    })->whereBetween('date', [$start_date, $end_date]);
                })->paginate(10);
        $item_id = $this->item_id ?? null;
        $item = Item::find($item_id);

        return $items;
    }

    public function map($items) : array {
        return [
            $items->title,
            $items->call_number,
            $items->isbn,
            $items->checkouts ? $items->checkouts->count() : 0,
            Carbon::parse($items->created_at)->format('Y-m-d'),
        ];
    }

    public function headings() : array {
        return [
           'Book Title',
           'Call Number	',
           'ISBN',
           'Total Member Taken',
           'Created At'
        ];
    }
}
