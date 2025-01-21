<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class InventoryExport implements FromCollection, WithMapping, WithHeadings
{

    public $search_item_name;
    public function __construct($request)
    {
        $this->search_item_name = $request->search_item_name;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $dataDb = Item::query();
        if ($this->search_item_name) {
            $dataDb->where('title', 'like', '%' . $this->search_item_name . '%');
            $dataDb->orWhere('item_ref', 'like', '%' . $this->search_item_name . '%');
            $dataDb->orWhere('isbn', 'like', '%' . $this->search_item_name . '%');
            $dataDb->orWhere('author', 'like', '%' . $this->search_item_name . '%');
            $dataDb->orWhere('call_number', 'like', '%' . $this->search_item_name . '%');
            $dataDb->orWhere('location', 'like', '%' . $this->search_item_name . '%');
        }
        return $dataDb->with('checkout')->get();
    }

    public function map($item): array
    {
        if ($item->is_active == 1 && isset($item->checkout) && isset($item->checkout->user) && !empty($item->checkout->user->first_name)) {
            $status = "Taken";
        } else if ($item->status == 1) {
            $status = "Available";
        } else {
            $status = "Un-Available";
        }
        if ($item->is_active == 1 && isset($item->checkout) && isset($item->checkout->user) && !empty($item->checkout->user->first_name)) {
            $issued_to = $item->checkout->user->first_name;
        } else {
            $issued_to = "Nil";
        }

        return [
            $item->title,
            $item->author,
            $item->isbn,
            $item->call_number,
            $item->barcode,
            $item->subject,
            $item->rfid,
            $item->location,
            $issued_to,
            $status
        ];
    }

    public function headings(): array
    {
        return [
            'Book Name',
            'Author',
            'ISBN',
            'Call Number',
            'Accession / Barcode Number',
            'Subject',
            'RFID',
            'Location',
            'Issued To',
            'Status'
        ];
    }
}
