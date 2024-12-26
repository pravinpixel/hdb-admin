
@extends('layouts.default')
@section('content')
    @include('flash::message')
    @include('flash')
    <div class="row">
       <div class="col-sm-12 col-md-6">         
            <h2 class="text-dark">Employee Dashboard</h2>
        </div>
   </div>
    {{-- <div class="container"> --}}
        <div class="row">
            <div class="col-md-3 col-xl-3">
                <div class="card bg-c-blue order-card">
                    <div class="card-block">
                        <h6 class="m-b-20"> Total Item Taken </h6>
                        <h2 class="text-right"><i class="fa fa-cart-plus f-left"></i><span> {{ $data['total_item_taken'] }}</span></h2>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-xl-3">
                <div class="card bg-c-green order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Item Return</h6>
                        <h2 class="text-right"><i class="fa fa-rocket f-left"></i><span>{{ $data['total_item_return'] }}</span></h2>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-xl-3">
                <div class="card bg-c-yellow order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Item to be Returned</h6>
                        <h2 class="text-right"><i class="fa fa-refresh f-left"></i><span>{{ $data['total_item_to_be_return'] }}</span></h2>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 col-xl-3">
                <div class="card bg-c-pink order-card">
                    <div class="card-block">
                        <h6 class="m-b-20">Total Item Requested</h6>
                        <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>{{ $data['total_item_requested'] }}</span></h2>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
   <div class="card">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Item Name / Item ID:</label>
                            {!! Form::text('item_name', null, ['class' => 'form-control', 'id' => 'item_name']) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <br>
                            <input type="button" id="search-item" class="btn btn-info" value="search">
                            <input type="button" id="reset-item" onclick="reset()" class="btn btn-danger" value="reset">
                        </div>
                    </div>
                </div>
                <div class="row">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="item-table" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Genre</th>
                                <th>Status</th>
                                <th>Approval Request</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
   </div>
    <div class="card">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-6"> 
                <h3 class="text-inverse">Request for Approval</h3>
                <div class="card">
                    <div class="table-responsive">
                        @include('employee.request-for-approval-table')
                    </div>
                </div>    
            </div>
            <div class="col-sm-12 col-md-12 col-lg-6"> 
                <h3 class="text-inverse">Past Item Taken</h3>
                    <div class="card">
                        <div class="table-responsive">
                            @include('employee.past-item-taken-table')
                        </div>   
                    </div>    
            </div>
        </div>
     </div>
     {{-- modal start --}}
    <div class="modal" tabindex="-1" role="dialog" id="item-modal">
        <div class="modal-dialog" role="document" style="width:70%">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Item View</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="item-modal-content"> </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
     {{-- modal end start --}}
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
$("#item-table").hide();
ItemDetails = [];
 $(function () {
    $("#search-item").click(function(){
        if($("#item_name").val() == '') {
            errorAlert('Item field is required');
            return false;
        }
      $.ajax({
          url: '{{route('item.get-item')}}',
          type: 'get',
          data:{item: $("#item_name").val()},
          success: function(res){
              if(res.status == true) {
                $("#item-table").show();
                ItemDetails = [...res.data];
                ItemTable();
              } else {
                  errorAlert('No data found');
              }
             //$("#item-data").html(res);
          },
          error: function(){
            errorAlert('Item not found');
          }
      });
    });

    function ItemTable(res) {
        jQuery("#item-table tbody").html('');
            ItemDetails.map((data,index) => {
            index++;
            let item = data.item;
            var newRowContent = `<tr>
                <td> ${index} </td>
                <td> ${item.item_id} </td>
                <td> ${item.item_name} </td>
                <td> ${item.category.category_name} </td>
                <td> ${item.genre.genre_name} </td>
                <td> ${data.checkout} </td>
                <td> ${data.approval_request} </td>
                <td> ${data.view} </td>
            </tr>`;
            jQuery("#item-table tbody").append(newRowContent);
        });
      
    }
    //request for approval
    var table = $('#request-for-approval-table').DataTable({
        aaSorting     : [[0, 'desc']],
        responsive: true,
        processing: true,
        pageLength: 50,    
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        searching: false, paging: true,info: true,
        bSort: false,
        serverSide: true,
        ajax          : {
            url     : '{!! route('employee.request-for-approval-datatable') !!}',
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {
                data: 'item', name: 'item'
            },
            {
                data: 'created_at', name: 'created_at'
            },
            {
                data: 'approve_status', name: 'approve_status'
            }
        ],
    });


  var table = $('#past-item-taken-table').DataTable({
        aaSorting     : [[0, 'desc']],
        responsive: true,
        processing: true,
        pageLength: 50,      
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        searching: false, paging: true,info: true,
        bSort: false,
        serverSide: true,
        ajax          : {
            url     : '{!! route('employee.past-item-taken') !!}',
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {
                data: 'item', name: 'item'
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
                    $("#search-item").trigger('click');
                    successAlert(res.msg);
                    $('#request-for-approval-table').DataTable().ajax.reload();
                    return true;
               }
                errorAlert(res.msg);
            },
            error : function(e) {
                errorAlert('Something went wrong try again');
            }
        }); 
    }); 

    $(document).on('click','.view-item',  function(){
        let item_id = $(this).data('item-id');
        let baseurl = $("#baseurl").val();
        $.ajax({
          url: `${baseurl}/master/item/${item_id}`,
          type: 'get',
          success: function(res){
              console.log(res);
              $("#item-modal").modal('show');
              $("#item-modal-content").html(res);
          },
          error: function(){
            errorAlert('Item not found');
          }
      });
    });
});
function reset()
{
    $("#item-table").hide();
    jQuery("#item-table tbody").html('');
    ItemDetails.length = 0;
}
</script>
@endpush

     
