
@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Return </h2>
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
               <li class="breadcrumb-item active" aria-current="page">Return</li>
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
                     <input type="text" id="member_id"  value="" onkeypress="getEmpdetails(event)" name="member_id" class="form-control" style="height: 50px">
                  @else
                     <input type="text" id="member_id"  value="{{$emp_id}}" readonly onkeypress="getEmpdetails(event)" name="member_id" class="form-control" style="height: 50px">
                  @endif
                   <input type="hidden" id="memberid" name="memberid" value="{{$emp_id}}">
                  </div>
               </div>
            </div>
            <input type="hidden" id="user_id" name="user_id" class="form-control" style="height: 50px">
         </div> 
         <div id="Employee-details"> </div>
         <hr>
         <div class="col-lg-12">
            <div id="all-taken-item"></div>
         </div>
      </div>
   </div>

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
                     <input type="text" id="item_id" onkeypress="getTakenItems(event)" name="item_id" class="form-control" style="height: 50px">
                  </div>
               </div>
            </div>
             {{-- @if(Sentinel::inRole('admin') || Sentinel::hasAccess('return.get-taken-item'))
               <div class="container">
               <input type="button" onclick="getTakenItems()" class="btn btn-info" value ="search">
               <br><br>
               </div>
            @endif --}}
            <div class="col-lg-12">
               <div class="col-lg-12">
                  <label for="not-need-approval">Return Items:</label>
                  <table class="table table-bordered table-hover" id="item-table" width="100%">
                     <thead>
                     <tr>
                        <th>#</th>
                        <th>@lang('issue.item_image')</th>
                        <th>@lang('issue.item_id')</th>
                        <th>@lang('issue.item_name')</th>
                        <th>@lang('issue.date_of_return')</th>
                        <th>@lang('global.action')</th>
                     </tr>
                     </thead>
                     <tbody></tbody>
                  </table>
               </div>
            </div>
         </div> 
      </div>
   </div>
   <div class="col-lg-12">
   @if(Sentinel::inRole('admin') || Sentinel::hasAccess('return.checkin'))
         <button class="btn btn-success waves-effect waves-light" id="checkin-item">Return</button>
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
const takenItemDetails = {};
$(function(){
   $("#member_id").focus();
   var emp_id = $("#memberid").val();
   console.log(emp_id);
   if(emp_id != 'false') {
      $("#member_id").prop('readonly', true);
      getEmpdetails({keyCode:13});
   }
});

$(document).on('click', '.delete-item', function(){
      let checkout_id = $(this).data("checkout-id");
      delete takenItemDetails[checkout_id];
      takenItemTable();
      $(this).closest('tr').hide();
      successAlert('Item removed successfully');
});

$(document).on('click','#checkin-item', function(){
      if( $("#member_id").val() == '' ){
         errorAlert('Emp code field is required');
         return false;
      }
      if(Object.keys(takenItemDetails).length <= 0) {
         errorAlert('Add any item to return');
         return false;
      }
      $("#checkin-item").prop('disabled', true);
      $.ajax({
         url: '{{ route('return.checkin') }}',
         type: 'POST',
         data: {user_id: $("#user_id").val(), checkout_id: Object.keys(takenItemDetails) },
         success: function(res) {
            if(res.status) {
               successAlert(res.msg);
               $("#item-table > tbody").empty();
               restEmpdetails();
               if( $("#member_id").val() != '' ) { //only for employee and manager
                  getAlltakenItem();
               }
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
    $("#checkin-item").prop('disabled', false);
   });

function getEmpdetails(event){
   if(event.keyCode != 13) {
      return false;
   }
   if( $("#member_id").val() == ''){
      errorAlert('Emp code field is required');
      return false;
   }
   var memeber_id  = $("#member_id").val();
   $.ajax({
      url: '{{ route('user.get-user-details') }}',
      type: 'GET',
      data: { memeber_id : memeber_id },
      dataType: 'JSON',
      success: function(res) {
         if(res.status) {
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
                     <input type="hidden" id="_checkin_id" name="_checkout_id">
                  </div>
               </div>
            </div> `
            )
            $("#item-detail").show();
            $("#item_id").focus();
            $("#user_id").val(res.data.id);
            getAlltakenItem();
            return true;
         } else {
            if($("#memberid").val() == 'false') {
               $("#member_id").val('');
            }
            $("#error-message").html(res.msg);
            $("#error-modal").modal('show');
            return false;
         }
      },
      error: function(e) {
         errorAlert('Employee not found');
      }
   });
}

function getTakenItems() {
   if(event.keyCode != 13) {
      return false;
   }
   if( $("#item_id").val() == ''){
      errorAlert('Item code field is required');
      return false;
   }
   var user_id = $("#user_id").val();
   var item_id = $("#item_id").val();
   $("#member_id").prop('readonly', true);
   $.ajax({
      url: '{{ route('return.get-taken-item') }}',
      type: 'GET',
      data: { user_id : user_id, item_id: item_id },
      dataType: 'JSON',
      success: function(res) {
         $("#item_id").val('');
         if(res.status) {
           takenItemDetails[res.data.checkout_id] = res.data;
           takenItemTable();
           return true;
         }
         errorAlert(res.msg);
      },
      error: function(e) {
         errorAlert('Item not found');
      }
   })
}


function takenItemTable()
{
   jQuery("#item-table tbody").html('');
   Object.values(takenItemDetails).map((data,index) => {
         index++;
         var newRowContent = `<tr>
            <td> ${index} </td>
            <td> ${data.item_image} </td>
            <td> ${data.item_id} </td>
            <td> ${data.item_name} </td>
            <td> ${data.date_of_return} </td>
            <td> <a href='#' class="delete-item btn btn-danger btn-sm" data-checkout-id="${data.checkout_id}"> <i class="fa fa-trash" aria-hidden="true"></i> </a> </td>
         </tr>`;
         jQuery("#item-table tbody").append(newRowContent);
      });
}

function getAlltakenItem() 
{
   var user_id = $("#user_id").val();
   $("#member_id").prop('readonly', true);
   $.ajax({
      url: '{{ route('return.get-all-taken-item') }}',
      type: 'GET',
      data: { user_id : user_id },
      success: function(res) {
         if(res) {
           $("#all-taken-item").html(res)
           return true;
         }
         errorAlert(res.msg);
      },
      error: function(e) {
         errorAlert('Item not found');
      }
   })
}

function restEmpdetails()
{
   $("#item_id").val('');
   var emp_id = $("#memberid").val();
   DeleteKeys(takenItemDetails, Object.keys(takenItemDetails));
   if(emp_id != 'false') {
      $("#member_id").prop('readonly', true);
      takenItemTable();
      return false;
   }
   $("#all-taken-item").html('');
   $("#item-detail").hide();
   $("#member_id").val('');
   $("#member_id").prop('readonly', false);
   $("#Employee-details").html('');
   jQuery("#approval-item-table tbody").html('');
   jQuery("#item-table tbody").html('');
}
</script>
@endpush