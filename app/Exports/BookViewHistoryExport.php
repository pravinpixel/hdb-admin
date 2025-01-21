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
    public $item;
    public $start_date;
    public $end_date;

    public function __construct($request)
    {
        $this->item = $request->item;
        $this->start_date = $request->start_date;
        $this->end_date = $request->end_date;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {

        $start_date = $this->start_date ?? null;
        $end_date = $this->end_date ?? null;
        if (isset($start_date)) {
            $start_date = Carbon::parse($start_date)->startOfDay();
            $end_date = Carbon::parse($end_date)->endOfDay();
        }
        $item_id = $this->item ?? null;
        $items = Item::with(['language', 'checkouts' => function ($q) {
            $q->when(!Sentinel::inRole('admin'), function ($q) {
                $q->where('checkout_by', Sentinel::getUser()->id);
            });
        }])
            ->when(isset($item_id), function ($q) use ($item_id) {
                $q->where('id', $item_id);
            })->when(isset($start_date) && isset($end_date), function ($q) use ($start_date, $end_date) {
                $q->whereBetween('created_at', [$start_date, $end_date]);
            })
            ->get();
        $item_id = $this->item_id ?? null;
        $item = Item::find($item_id);

        return $items;
    }

    public function map($items): array
    {
        return [
            $items->title,
            $items->call_number,
            $items->isbn,
            $items->checkouts ? $items->checkouts->count() : 0,
            Carbon::parse($items->created_at)->format('Y-m-d'),
        ];
    }

    public function headings(): array
    {
        return [
            'Book Title',
            'Call Number	',
            'ISBN',
            'Total Member Taken',
            'Created At'
        ];
    }
}
