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
        if($this->item_id) {
            $dataDb->where('item_id',$this->item_id);
            $dataDb->orWhere('item_name',$this->item_id);
        } 
        if($this->category) {
            $dataDb->where('category_id',$this->category);
        }
        if($this->subcategory) {
            $dataDb->where('subcategory_id',$this->subcategory);
        }
        return $dataDb->with('category','subcategory','type','genre','user')->get();
    }
 
    public function map($item) : array {
        return [
            $item->item_id,
            $item->item_name,
            $item->category->category_name,
            $item->subcategory->subcategory_name,
            $item->type->type_name,
            $item->genre->genre_name,
            ($item->is_need_approval == 1)? 'Yes' : 'No',
            ($item->is_issued == 1)? 'N/A' : 'Available',
            $item->user->full_name ?? 'Nill',
        ];

    }

    public function headings() : array {
        return [
           'Item ID',
           'Item Name',
           'Category',
           'Subcategory',
           'Type',
           'Genre',
           'Is Need Approval',
           'Is Issued',
           'Issued To'
        ] ;
    }
}
