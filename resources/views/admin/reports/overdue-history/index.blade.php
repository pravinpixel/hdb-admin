
@extends('layouts.default')
@section('content')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Ovedue History Report </h2>
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
               <li class="breadcrumb-item"><a href="#">Overdue History</a></li>
            </ol>
         </nav>
      </div>
   </div>
   
    <div class="row">
        
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card bg-green">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white"> Overdue Book </h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="overdue"><span>  0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
        

   <div class="card">
          {!!  Form::open(['route' => 'overdue-history.export', 'method' => 'post']) !!}
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start">Start Date (date of return):</label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" name="start_date" class="form-control" id="start_date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end">End Date (date of return):</label>
                        <div class="input-group date" data-provide="datepicker">
                            <input type="text" name="end_date" class="form-control" id="end_date">
                            <div class="input-group-addon">
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Staff:</label>
                        {!! Form::select('member',[], null,[ 'placeholder' => 'select Staff', 'class' => 'form-control', 'id' => 'member']) !!}
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
            @include('admin.reports.overdue-history.table')
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
    var start_date = '{!! Date("d-m-Y", strtotime($start_date) )!!}';
    var end_date = '{!! Date("d-m-Y", strtotime($end_date) )!!}';

    $("#start_date").val(start_date);
    $("#end_date").val(end_date);
    
    $("#member").select2({
        ajax: {
            url : '{!! route('overdue-history.get-member-dropdown') !!}',
            data: function (params) {
                return { q: params.term }
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    });

   var table = $('#overdueHistoryTable').DataTable({
         aaSorting     : [[0, 'desc']],
         responsive: true,
         processing: true,    
         pageLength: -1,
         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
         serverSide: true,
        searching: false, paging: true, info: true,    
        bSort: false,
         ajax          : {
            url     : '{!! route('overdue-history.datatable') !!}',
            dataType: 'json',
            data : function(d){
                d.member_id = $("#member").val();
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
            },
         },
         columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'item.item_ref', name: 'item_id'},
            {data: 'item.isbn', name: 'item.isbn'},
            {data: 'user.first_name', name: 'user.first_name'},
            {data: 'date', name: 'date'},
             {data: 'checkout_date', name: 'checkout_date'},
            {data: 'overdue_days', name: 'overdue_days'},
            {data: 'created_at', name: 'created_at'},
         ],
   });
    table.on( 'xhr', function () {
    var json = table.ajax.json();
        let card = json.card;
        $('#overdue').html(card.overdue);
    });
   $("#search-item").click( function(e){
        e.preventDefault();
        $('#overdueHistoryTable').DataTable().clear().draw();
   });

   $("#reset-item").click( function(e){
        e.preventDefault();
        $("#member").val('').trigger('change');
        $('#overdueHistoryTable').DataTable().clear().draw();
   });
}); 
</script>
@endpush