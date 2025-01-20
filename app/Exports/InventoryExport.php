<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class InventoryExport implements FromCollection, WithMapping, WithHeadings
{

    public $category;
    public $subcategory;
    public $item_id;
    public function __construct($request)
    {
        $this->category = $request->category;
        $this->subcategory = $request->subcategory;
        $this->item_id = $request->search_item_name;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $dataDb = Item::query();
        if ($this->item_id) {
            $dataDb->where('item_id', $this->item_id);
            $dataDb->orWhere('item_name', $this->item_id);
        }
        if ($this->category) {
            $dataDb->where('category_id', $this->category);
        }
        if ($this->subcategory) {
            $dataDb->where('subcategory_id', $this->subcategory);
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
