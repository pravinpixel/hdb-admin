
@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Borrow </h2>
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
               <li class="breadcrumb-item active" aria-current="page">Borrow</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
            <div class="col-lg-12">
               <div class="col-lg-4">
                  <div class="form-group">
                     <h3><b>Scan/Enter the EMP code</b></h3>
                  </div>
               </div>
               <div class="col-lg-8">
                  <div class="form-group">
                  @if($emp_id == 'false')
                     <input type="text" id="member_id" value="" onkeypress="getEmpdetails(event)" name="member_id" class="form-control" style="height: 50px">
                  @else
                     <input type="text" id="member_id" value="{{$emp_id}}" onkeypress="getEmpdetails(event)" readonly name="member_id" class="form-control" style="height: 50px">
                  @endif
                  <input type="hidden" id="memberid" name="memberid" value="{{$emp_id}}">
                  </div>
               </div>
            </div>
         </div> 
         <div id="Employee-details"> </div>
      </div>
   </div>
   <input type="hidden" id="checkout_id" name="checkout_id">
   <div class="card" id="item-detail" style="display:none">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
            <div class="col-lg-12">
               <div class="col-lg-4">
                  <div class="form-group">
                     <h3><b>Scan/Enter the Item code</b></h3>
                  </div>
               </div>
               <div class="col-lg-8">
                  <div class="form-group">
                     <input type="text" id="item_id" onkeypress="getItemdetails(event)" name="item_id" class="form-control" style="height: 50px">
                  </div>
               </div>
            </div>
            {{-- @if(Sentinel::inRole('admin') || Sentinel::hasAccess('issue.get-item'))
               <div class="container">
               <input type="button" onclick="getItemdetails()" class="btn btn-info" value ="search">
               <br><br>
               </div>
            @endif --}}
            <div class="col-lg-12">
               
               <div class="col-lg-6">
                  <label for="not-need-approval">Items that do not require approval:</label>
                  <table class="table table-bordered table-hover" id="item-table" width="100%">
                     <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('issue.item_id')</th>
                        <th>@lang('issue.item_name')</th>
                        <th>@lang('issue.status')</th>
                        <th>@lang('global.action')</th>
                     </tr>
                     </thead>
                     <tbody></tbody>
                  </table>
               </div>

               <div class="col-lg-6">
                  <label for="need-aproval">Items that require approval:</label>
                  <table class="table table-bordered table-hover" id="approval-item-table" width="100%">
                     <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('issue.item_id')</th>
                        <th>@lang('issue.item_name')</th>
                        <th>@lang('issue.status')</th>
                        <th>@lang('issue.approved')</th>
                        <th>@lang('global.action')</th>
                     </tr>
                     </thead>
                     <tbody></tbody>
                  </table>
               </div>
            </div>
            <div class="col-lg-12">
              <div id="checkout-count"> </div>
            </div>
         </div> 
      </div>
   </div>
   <div class="col-lg-12">
      @if(Sentinel::inRole('admin') || Sentinel::hasAccess('issue.checkout'))
         <button class="btn btn-success waves-effect waves-light" id="checkout-item">Checkout</button>
      @endif
      
      <input type="button" onclick="restEmpdetails()" class="btn btn-danger" value ="Reset">
    
   </div>
@stop

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('dark/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/pages/issue.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
const itemID = {};
const itemDetails = {};
const approvalItemDetails = {};
const checkoutCount = {};
var Item_id = undefined;
$(function(){
   $("#member_id").focus();
   var emp_id = $("#memberid").val();
   if(emp_id != 'false') {
      $("#member_id").prop('readonly', true);
      getEmpdetails({keyCode:13});
   }
   $(document).on('click', '.delete-item', function(){
      let item_id = $(this).data("item-id");
      delete itemDetails[item_id];
      delete itemID[item_id];
      $(this).closest('tr').hide();
      getcheckoutCount(item_id);
      successAlert('Item removed successfully');
   });
   $(document).on('click', '.approval-delete-item', function(){
      let item_id = $(this).data("approval-item-id");
      delete approvalItemDetails[item_id];
      delete itemID[item_id];
      $(this).closest('tr').hide();
      getcheckoutCount(item_id);
      successAlert('Item removed successfully');
   });

   $(document).on('click','#checkout-item', function(){
      if( $("#member_id").val() == '' ){
         errorAlert('Emp code field is required');
         return false;
      } 

      if(Object.values(itemID).length == 0 &&  Object.values(approvalItemDetails).length != 0) {
         let need_approval_item  = Object.values(approvalItemDetails).map((data)=> {
            return data.item.id;
         });
         sendApprovalRequest(need_approval_item, true);
         return false;
      }

      if( Object.values(itemID).length == 0 ) {
         errorAlert('Please add any item to checkout');
         return false;
      }

      if(Object.values(itemID).length > 0) {
          let need_approval_item  = Object.values(approvalItemDetails).map((data)=> {
            return data.item.id;
         });
         sendApprovalRequest(need_approval_item, false);
      }
      
      $("#checkout-item").prop('disabled', true);
      $.ajax({
         url: '{{ route('issue.checkout') }}',
         type: 'POST',
         data: {user_id: $("#checkout_id").val(), item_id: Object.values(itemID) },
         success: function(res) {
            if(res.status) {
               successAlert(res.msg);
               $("#approval-item-table > tbody").empty();
               $("#approval-item-table > tbody").empty();
               $("#item-table > tbody"). empty();
               restEmpdetails();
               DeleteKeys(checkoutCount, Object.keys(checkoutCount));
               $("#checkout-count").html(`Checkout Count: ${Object.keys(checkoutCount).length}`);
               return true;
            }
            errorAlert(res.msg);
            return false;
         },
         error: function(){
            errorAlert('Something went wrong');
         }
      });
   });
   $( document ).ajaxStop(function() {
    $("#checkout-item").prop('disabled', false);
   });
});

function sendApprovalRequest(item, alert) {
   if(item.length <= 0){
      //errorAlert('Something went wrong contact administrator');
      return false;
   }
   $.ajax({
         url: '{{ route('issue.send-approval-request') }}',
         type: 'POST',
         data: {member_id: $("#member_id").val(), item_id: item },
         success: function(res) {
            if(alert) {
               if(res.status) {
                  $("#approval-item-table > tbody").empty();
                  successAlert(res.msg);
                  restEmpdetails();
                  return true;
               }
               errorAlert(res.msg);
               return false;
            }
         },
         error: function(){
            errorAlert('Something went wrong contact administrator');
         }
   });
}

function getApprovedItem(id){
   var table = $('#issue-table').DataTable({
         aaSorting     : [[0, 'desc']],
         responsive: true,
         processing: true,    
         lengthMenu: [[-1], ["All"]],
         serverSide: false,
         bSort: false,
         searching: false, paging: false,info: true,
         ajax          : {
            url     :  `${$("#baseurl").val()}/issue/datatable/1`,
            dataType: 'json'
         },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'item.item_id', name: 'item.item_id'},
            {data: 'item.item_name', name: 'item.item_name'},
            {data: 'item.category.category_name', name: 'category.category_name'},
            {data: 'item.subcategory.subcategory_name', name: 'subcategory.subcategory_name'},
            {data: 'item.genre.genre_name', name: 'genre.genre_name'},
            {data: 'item.type.type_name', name: 'type.type_name'},
            {data: 'date_of_return', name: 'date_of_return'},
            {
               data         : 'action', name: 'action', orderable: false, searchable: false,
               fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                     //  console.log( nTd );
                     $("a", nTd).tooltip({container: 'body'});
               }
            }
        ],
    });

}

function getEmpdetails(event){
   if(event.keyCode != 13) {
      return false;
   }
   if( $("#member_id").val() == ''){
      errorAlert('Emp code field is required');
      return false;
   }
   $.ajax({
      url: '{{ route('user.get-user-details') }}',
      type: 'GET',
      data: { memeber_id : $("#member_id").val() },
      dataType: 'JSON',
      success: function(res) {
         if(res.status) {
             $("#checkout_id").val(res.data.id);
             $("#Employee-details").html(
               `<div class="col-sm-12 col-md-12">   
               <div class="col-lg-12">
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="name">Employee Name : </label>
                        <span> ${res.data.first_name} </span>
                     </div>
                     <div class="form-group">
                        <label class="email">Email : </label>
                        <span> ${res.data.email} </span>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="form-group">
                        <label class="mobile">Mobile : </label>
                        <span> ${res.data.mobile} </span>
                     </div>
                     <div class="form-group">
                        <label class="address">Address : </label>
                         <span> ${res.data.address} </span>
                     </div> 
                  </div>
               </div>
            </div> `);
            $("#item-detail").show();
            $("#item_id").focus();
            return true;
         } else {
            if($('#memberid').val() == 'false') {
               $("#member_id").val('');
            }
            $("#item-detail").hide();
            $("#Employee-details").html('');
            $("#error-message").html(res.msg);
            $("#error-modal").modal('show');
            return false;
         }
      },
      error: function(e) {
         if($('#memberid').val() == 'false') {
            $("#member_id").val('');
         }
         errorAlert('Employee not found');
         $("#item-detail").hide();
         $("#Employee-details").html('');
      }
   })
}

function getItemdetails(event){
   if(event.keyCode != 13) {
      return false;
   }
   if( $("#item_id").val() == ''){
      errorAlert('Item code field is required');
      return false;
   }
   $("#member_id").prop('readonly', true);
   $.ajax({
      url: '{{ route('issue.get-item') }}',
      type: 'GET',
      data: { user_id : $("#checkout_id").val(), item : $("#item_id").val(), type: 'json' },
      dataType: 'JSON',
      success: function(res) {
         $("#item_id").val('');
         if(res.status) {
            if(res.data.item.is_issued == 1) {
               errorAlert(`Item already issued to ${res.data.item.user.first_name}`);
               return false;
            }
            if(res.data.item.is_need_approval == 1 && (res.data.waiting_for_request == null || res.data.waiting_for_request == 1)){
                approvalItemDetails[res.data.item.item_id] = res.data;
                appendApprovalItemTable();
            } else {
               itemDetails[res.data.item.item_id] = res.data;
               appendItemTable();
               itemID[res.data.item.item_id] = res.data.item.id;
            }
            $("#checkout-count").html(`Checkout Count: ${Object.keys(checkoutCount).length}`);
            return true;
         }
         errorAlert(res.msg);
      },
      error: function(e) {
         $("#item_id").val('');
         errorAlert('Item not found');
      }
   })
}
function appendItemTable()
{
         item_count = 0;
         jQuery("#item-table tbody").html('');
         Object.values(itemDetails).map((data,index) => {
               if(data.item.is_issued == 0) {
                  checkoutCount[data.item.item_id] = data.item.item_id;
               }
               index++;
               let newRowContent = `<tr>
                  <td> ${index} </td>
                  <td> ${data.item.item_id} </td>
                  <td> ${data.item.item_name} </td>
                  <td> ${(data.item.is_issued == 1)? "<div class='label label-danger label-sm'> issued to " + data.item.user.first_name+ " "+data.item.user.last_name + " </div>" : "<div class='label label-success label-sm'> available </div>"} </td>
                  <td> <a href='#' class="delete-item btn btn-danger btn-sm" data-item-id="${data.item.item_id}"> <i class="fa fa-trash" aria-hidden="true"></i> </a> </td>
               </tr>`
               jQuery("#item-table tbody").append(newRowContent);
            });

}

function appendApprovalItemTable()
{
         jQuery("#approval-item-table tbody").html('');
         Object.values(approvalItemDetails).map((data,index) => {
                              index++;
               let approvalNewRowContent = `<tr>
                  <td> ${index} </td>
                  <td> ${data.item.item_id} </td>
                  <td> ${data.item.item_name} </td>
                  <td> ${(data.item.is_issued == 1)? "<div class='label label-danger label-sm'> issued to " + data.item.user.first_name+ " "+data.item.user.last_name + " </div>" : "<div class='label label-success label-sm'> available </div>"} </td>
                  <td> ${(data.waiting_for_request == 1) ? "<div class='label label-info label-sm'> waiting for approval </div>" : "<div class='label label-danger label-sm'> no </div>" } </td>
                  <td> <a href='#' class="approval-delete-item btn btn-danger btn-sm" data-approval-item-id="${data.item.item_id}"> <i class="fa fa-trash" aria-hidden="true"></i> </a> </td>
               </tr>`
               jQuery("#approval-item-table tbody").append(approvalNewRowContent);
            });
}
  

function restEmpdetails()
{
   
   DeleteKeys(itemID, Object.keys(itemID));
   DeleteKeys(itemDetails, Object.keys(itemDetails));
   DeleteKeys(approvalItemDetails, Object.keys(approvalItemDetails));
   DeleteKeys(checkoutCount, Object.keys(checkoutCount));
   $("#item_id").val('');
   var emp_id = $('#memberid').val();
   if(emp_id != 'false') {
      $("#member_id").prop('readonly', true);
      appendItemTable();
      appendApprovalItemTable();
      $("#checkout-count").html(`Checkout Count: ${Object.keys(checkoutCount).length}`);
      return false;
   }
   $("#member_id").prop('readonly', false);
   $("#member_id").val('');
   $("#item-detail").hide();
   $("#Employee-details").html('');
   jQuery("#approval-item-table tbody").html('');
   jQuery("#item-table tbody").html('');
   $("#checkout-count").html(`Checkout Count: ${Object.keys(checkoutCount).length}`);
   
}

function getcheckoutCount(Item_id){
   if(checkoutCount[Item_id] !== undefined) {
      delete checkoutCount[Item_id];
  }
   $("#checkout-count").html(`Checkout Count: ${Object.keys(checkoutCount).length}`);
}
</script>
@endpush