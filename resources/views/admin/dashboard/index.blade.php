
@extends('layouts.default')
@section('content')

@include('flash::message')
@include('flash')

            <div class="row">
                <div class="col-sm-12 col-md-6">         
                    <h2 class="text-dark">Admin Dashboard</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-4 col-xl-4">
                    <div class="card bg-blue">
                        <div class="card-block dashboard">
                            <i class="fa fa-cart-plus f-left"></i>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <h4 class="m-b-20 text-white"> Total Items</h4>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                                    <h1 class="text-right text-white"><span> {{$data['total_item'] ?? 0}} </span></h1>       
                                </div>
                            </div>            
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 col-md-4 col-xl-4">
                    <div class="card bg-purple">
                        <div class="card-block dashboard">
                            <i class="fa fa-rocket f-left"></i>
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <h4 class="m-b-20 text-white">Total Issued</h4>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5 class="m-b-20 text-white text-center">&nbsp;</h5>
                                    <h1 class="text-right text-white"><span>{{ $data['total_issued'] ?? 0}}</span></h1> 
                                </div>
                            </div>                  
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12 col-md-4 col-xl-4">
                    <div class="card bg-orange">
                        <div class="card-block dashboard">    
                            <i class="fa fa-refresh f-left"></i>                        
                            <div class="row">
                                <div class="col-md-12 col-sm-12">
                                    <h4 class="m-b-20 text-white">Total Item to be Returned</h4>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="m-b-20 text-white text-center">Today</h5>
                                    <h1 class="text-white text-center"><span> {{ $data['total_item_to_be_returned_today'] ?? 0 }}</span></h1>     
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="m-b-20 text-white text-center">Tomorrow</h5>
                                    <h1 class="text-white text-center"><span>{{ $data['total_item_to_be_returned_tomorrow'] ?? 0 }}</span></h1>   
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <h5 class="m-b-20 text-white text-center">This Week</h5>
                                    <h1 class="text-white text-center"><span>{{ $data['total_item_to_be_returned_week'] ?? 0 }}</span></h1>   
                                </div>
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-sm-12 col-md-6">         
                    <h3 class="text-inverse">Items</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12"> 
                    <div class="card">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="type">Item Name / Item ID:</label>
                                        {!! Form::text('search_item_name', null, ['class' => 'form-control', 'id' => 'search_item_name']) !!}
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="width:100%;">&nbsp;</label>
                                            <button class="btn btn-success" id="search-item"> Search </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label style="width:100%;">&nbsp;</label>
                                            <button class="btn btn-info" id="reset-item"> Reset </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        <br>
                        <div class="row">
                            <div class="col-sm-12 col-md-12"> 
                                @include('admin.dashboard.table')
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
@endsection

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/pagec/employee.css') }}">
    <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('dark/assets/plugins/select2/dist/js/select2.min.js')}}"> </script>
<script>
 $(function () {

    $("#type").select2({
       ajax: {
         url : '',
         data: function (params) {
            return { q: params.term }
         },
         processResults: function (data) {
            return { results: data };
         }
      }
    });

    var table = $('#admin-dashboard-table').DataTable({
        aaSorting     : [[0, 'desc']],
        responsive: true,
        processing: true,
        pageLength: 50,    
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        serverSide: true,
        searching: false, paging: true,info: true,
        bSort: false,
        ajax          : {
            url     : '{!! route('admin.datatable') !!}',
            dataType: 'json',
            data:   function(d){
                d.search_item_name = $("#search_item_name").val();
            }
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {
                data: 'item_id', name: 'item_id'
            },
            {
                data: 'item_name', name: 'item_name'
            },
            {
                data: 'date_of_return', name: 'date_of_return'
            },
            {
                data: 'status', name: 'status'
            } 
        ],
    });
    $("#remove-form").submit(function(e) {
        e.preventDefault();
        var url = $("#remove-form").attr('action');
         $("#delete").modal('hide');
        $.ajax({
            url: url,  
            type: 'PUT',
            datatype: 'JSON',
            success : function(res) {
               if(res.status) {
                    $('#emplyee-dashboard-table').DataTable().clear().draw();
                    $("#success-message").html(res.msg);
                    $("#success-modal").modal('show');
                    return true;
               }
               $("#error-message").html(res.msg);
               $("#error-modal").modal('show');
               return false;
            },
            error : function(e) {
                $("#error-message").html("Something went wrong try again");
                $("#error-modal").modal('show');
                console.log(`approve-request: ${e}`);
            }
        }); 
    });

    $("#search-item").click(function(){
        $('#admin-dashboard-table').DataTable().clear().draw();
    });

    $("#reset-item").click(function(){
        $('#search_item_name').val('');
        $('#subcategory').val('').trigger('change');
        $('#category').val('').trigger('change');
        $('#genre').val('').trigger('change');
        $('#type').val('').trigger('change');
        $('#admin-dashboard-table').DataTable().clear().draw();
    });
            
});
</script>
@endpush

     
