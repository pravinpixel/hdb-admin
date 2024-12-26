
<style type="text/css">
.table>tbody>tr>td{
    border: none;
    border-top: 1px solid #f1f1f1;
    text-align: left;
}
</style>
<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
            <tr>
                <td colspan="4" class="text-left"> <img src="{{asset('uploads/'.$data['item']->cover_image)}}" border="0" width="100" class="img-rounded" align="center" alt="No image found"> </td>
            </tr>
            <tr>
                <td class="text-left"><strong>Item ID</strong></td>
                <td class="text-left">{{ $data['item']->item_id}}</td>
                <td class="text-left"><strong>Item Name</strong></td>
                <td class="text-left"> {{ $data['item']->item_name}}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Item Category</strong></td>
                <td class="text-left">{{ $data['item']->category->category_name}}</td>
                <td class="text-left"><strong>Item Subcategory</strong></td>
                <td class="text-left">{{ $data['item']->subcategory->subcategory_name}}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Item Type</strong></td>
                <td class="text-left">{{ $data['item']->type->type_name}}</td>
                <td class="text-left"><strong>Item Genre</strong></td>
                <td class="text-left">{{ $data['item']->genre->genre_name}}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Loan Days</strong></td>
                <td class="text-left">{{ $data['item']->loan_days}}</td>
                <td class="text-left"><strong>No of pages</strong></td>
                <td class="text-left">{{ $data['item']->no_of_page}}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Item Type</strong></td>
                <td class="text-left">{{ $data['item']->type->type_name}}</td>
                <td class="text-left"><strong>Item Genre</strong></td>
                <td class="text-left">{{ $data['item']->genre->genre_name}}</td>
            </tr>
            <tr>
                <td class="text-left"><strong>Status</strong></td>
                <td class="text-left">
                    @if($data['item']->checkout_by)
                        <a class="label label-danger label-sm"> Issued to {{  ($data['item']->user->id == Sentinel::getUser()->id) ? 'You' : $data['item']->user->full_name }}</a>
                    @else 
                        <a class="label label-success label-sm"> Available </a>
                    @endif
                </td>
                <td class="text-left"><strong>Is Need Approval</strong></td>
                <td class="text-left">{{ ($data['item']->is_need_approval) ? 'Yes':'No' }}</td>
            </tr>
        </tbody>
    </table>
</div>