
@extends('layouts.default')
@section('content')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Inventory Report </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('employee'))
               <li class="breadcrumb-item"><a href="{{route('employee.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item"><a href="#">Inventory List</a></li>
            </ol>
         </nav>
      </div>
   </div>
   
    <div class="row">
        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card bg-blue">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-cart-plus f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white"> Total Books</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total_item"><span> 0 </span></h1>       
                        </div>
                    </div>            
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card bg-green">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white">Total Issued Books</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total_issued_item"><span> 0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card bg-purple">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white">Total Active Books</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total_active_item"><span>  0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-3 col-xl-3">
            <div class="card bg-orange">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white">Total Inactive Books</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total_inactive_item"><span> 0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
        

   <div class="card">
    {!!  Form::open(['route' => 'inventory.export', 'method' => 'post']) !!}
        <div class="row">
            <div class="col-sm-12 col-md-12"> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Book Name / RFID:</label>
                        {!! Form::text('search_item_name', null, ['class' => 'form-control', 'id' => 'search_item_name']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Language:</label>
                        {!! Form::select('category',[], null,[ 'placeholder' => 'select language', 'class' => 'form-control', 'id' => 'category']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label style="width:100%;">&nbsp;</label>
                        <button class="btn btn-success" id="search-item"> Search </button>
                        <button class="btn btn-danger" id="reset-item"> Reset </button>
                        <button class="btn btn-warning" id="export-item"> Export </button>
                    </div>
                </div>
            </div>
        </div>
    {!!  Form::close() !!}
        <div class="row">
            <div class="col-sm-12 col-md-12"> 
            @include('admin.reports.inventory.table')
            </div>    
        </div>
   </div>
@stop

@push('page_css')
    
   <link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
   <link rel="stylesheet" href="{{ asset('assets/pagec/employee.css') }}">
   <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('dark/assets/plugins/select2/dist/js/select2.min.js')}}"> </script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>

$(function () {

  

  //  var table = $('#inventoryTable').DataTable({
//          aaSorting     : [[0, 'desc']],
//          responsive: true,
//          processing: true,    
//          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
//          serverSide: true,
//          pageLength: 50,
//         searching: false, paging: true, info: true,    
//         bSort: false,
//          ajax          : {
//             url     : '',
//             dataType: 'json',
//             data: function(d) {
//                 d.search_item_name = $("#search_item_name").val();
//                 d.category = $("#category").val();
//                 d.subcategory = $("#subcategory").val();
//             }
//          },
//          columns       : [
//             {data: 'id', name: 'id', visible: false},
//             {data: 'item_id', name: 'item_id'},
//             {data: 'item_name', name: 'item_name'},
//             {data: 'category.category_name', name: 'category_name'},
//             {data: 'subcategory.subcategory_name', name: 'subcategory_name'},
//             {data: 'type.type_name', name: 'type_name'},
//             {data: 'genre.genre_name', name: 'genre_name'},
//             {data: 'status', name: 'status'},
//             {data: 'issued_to', name: 'issued_to'}
//          ],
//    });


   table.on( 'xhr', function () {
    var json = table.ajax.json();
        let card = json.card
        $('#total_active_item').html(card.total_active_item);
        $('#total_inactive_item').html(card.total_inactive_item);
        $('#total_issued_item').html(card.total_issued_item);
        $('#total_item').html(card.total_item);
    });


   $("#search-item").click( function(e){
        e.preventDefault();
        $('#inventoryTable').DataTable().clear().draw();
   });

   $("#reset-item").click( function(e){
        e.preventDefault();
        $("#search_item_name").val('');
        $("#category").val('').trigger('change');
        $("#subcategory").val('').trigger('change');
        $('#inventoryTable').DataTable().clear().draw();
   });
}); 
</script>
@endpush