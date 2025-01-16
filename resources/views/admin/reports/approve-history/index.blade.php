
@extends('layouts.default')
@section('content')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Approve History Report </h2>
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
               <li class="breadcrumb-item"><a href="#">Approve History</a></li>
            </ol>
         </nav>
      </div>
   </div>
   
    <div class="row">
        <div class="col-sm-12 col-md-4 col-xl-4">
            <div class="card bg-purple">
                <div class="card-block dashboard">
                
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white"> Total Request</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total-item"><span> 0 </span></h1>       
                        </div>
                    </div>            
                </div>
            </div>
        </div>
        
        <div class="col-sm-12 col-md-4 col-xl-4">
            <div class="card bg-danger">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white">Total Rejected Request</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id="total-rejected-item"><span> 0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-4 col-xl-4">
            <div class="card bg-success">
                <div class="card-block dashboard">
                    {{-- <i class="fa fa-rocket f-left"></i> --}}
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <h4 class="m-b-20 text-white">Total Accepted Request</h4>
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                            <h1 class="text-right text-white" id ="total-approved-item"><span> 0 </span></h1> 
                        </div>
                    </div>                  
                </div>
            </div>
        </div>
    </div>
        

   <div class="card">
          {!!  Form::open(['route' => 'approve-request.export', 'method' => 'post']) !!}
        <div class="row">
            <div class="col-sm-12 col-md-12"> 
            <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Select Status:</label>
                        {!! Form::select('status', $status, null, [ 'placeholder' => 'select status', 'class' => 'form-control', 'id' => 'status']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Select Staff:</label>
                        {!! Form::select('member',[], null,[ 'placeholder' => 'select Staff', 'class' => 'form-control', 'id' => 'member']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">Select Item:</label>
                        {!! Form::select('item',[], null,[ 'placeholder' => 'select item', 'class' => 'form-control', 'id' => 'item']) !!}
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
            @include('admin.reports.approve-history.table')
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

    $("#member").select2({
        ajax: {
            url : '{!! route('approve-request.get-member-dropdown') !!}',
            data: function (params) {
                return { q: params.term }
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    });

    $("#item").select2({
        ajax: {
            url : '{!! route('approve-request.get-item-dropdown') !!}',
            data: function (params) {
                return { q: params.term }
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    });

   var table = $('#approveHistoryTable').DataTable({
//          aaSorting     : [[0, 'desc']],
//          responsive: true,
//          processing: true,    
//         pageLength: 50,
//          lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
//          serverSide: true,
//         searching: false, paging: true, info: true,    
//         bSort: false,
//          ajax          : {
//             url     : '{!! route('approve-request.datatable') !!}',
//             dataType: 'json',
//             data: function(d) {
//                 d.member = $('#member').val();
//                 d.item = $('#item').val();
//                 d.status = $('#status').val();
//             }
//          },
//          columns       : [
//             {data: 'id', name: 'id', visible: false},
//             {data: 'created_at', name: 'created_at'},
//             {data: 'item.item_id', name: 'item_id'},
//             {data: 'item.item_name', name: 'item_name'},
//             {data: 'requested_user', name: 'requested_by'},
//             {data: 'approved_rejected_user', name: 'approved_rejected_user'}
//          ],
//    });

   table.on( 'xhr', function () {
    var json = table.ajax.json();
        let card = json.card;
        $("#total-approved-item").html(card.total_approved_item);
        $("#total-item").html(card.total_item);
        $("#total-rejected-item").html(card.total_rejected_item);
    });

   $("#search-item").click( function(e){
        e.preventDefault();
        $('#approveHistoryTable').DataTable().clear().draw();
   });

   $("#reset-item").click( function(e){
        e.preventDefault();
        $("#status").val('');
        $("#member").val('').trigger('change');
        $("#item").val('').trigger('change');
        $('#approveHistoryTable').DataTable().clear().draw();
   });
}); 
</script>
@endpush