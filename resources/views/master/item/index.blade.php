
@extends('layouts.default')
@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
@endpush
@section('content')
   @include('flash::message')
   @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Books </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('item.index') }}">Books</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card" style="overflow-x: auto;">
      <div class="row">
         <div class="col-sm-12 col-md-12 pb-20 text-right"> 
            <a href="{{ route('item.create') }}" class="btn btn-success" > <i class="fa fa-plus"></i> &nbsp; Add Book </a>              
         </div>  
         <div class="col-sm-12 col-md-12"> 
            @include('master.item.table')
         </div>    
      </div>
   </div>
@stop

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">\
   <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
   <link rel="stylesheet" href="{{ asset('assets/pagec/employee.css') }}">
@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>

$(function () {
   var table = $('#itemTable').DataTable({
         aaSorting     : [[0, 'desc']],
         responsive: true,
         processing: true,
         pageLength: 50,    
         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
         serverSide: true,
         ajax          : {
            url     : '{!! route('item.datatable') !!}',
            dataType: 'json'
         },
         columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'item_ref', name: 'rfid'},
            {data: 'title', name: 'title'},
            {data: 'author', name: 'author'},
            {data: 'isbn', name: 'isbn'},
            {data: 'call_number', name: 'call_number'},
            {data: 'barcode', name: 'barcode'},
            {data: 'subject', name: 'subject'},
            {data: 'language.language', name: 'language.language'},
            {data: 'created_at', name: 'created_at'},
            {data: 'updated_at', name: 'updated_at'},
            {
               data: 'status', name: 'status', fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                     $("a", nTd).tooltip({container: 'body'});
               }
            },
            {
               data         : 'action', name: 'action', orderable: false, searchable: false,
               fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                $(nTd).css('min-width', '100px');
                     $("a", nTd).tooltip({container: 'body'});
               }
            }
         ],
   });
}); 
</script>
@endpush