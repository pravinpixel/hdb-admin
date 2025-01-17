
@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Notifications </h2>
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
               <li class="breadcrumb-item active" aria-current="page">Notifications</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start">Start Date :</label>
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
                        <label for="end">End Date :</label>
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
                        <label style="width:100%;">&nbsp;</label>
                        <button class="btn btn-success" id="search-item"> Search </button>
                        <button class="btn btn-danger" id="reset-item"> Reset </button>
                    </div>
                </div>
            </div>
        </div>
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
            @include('admin.notification.table')
         </div>    
      </div>
   </div>
@stop

@push('page_css')
   <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">

@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>

$(function () {
   var table = $('#notificationTable').DataTable({
         aaSorting     : [[0, 'desc']],
         responsive: true,
         processing: true,
         pageLength: 50,    
         lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
         serverSide: true,
         ajax          : {
            url     : '{!! route('notification.datatable') !!}',
            data: function(d) {
                d.start_date = $("#start_date").val();
                d.end_date = $("#end_date").val();
            },
            dataType: 'json'
         },
         columns       : [
            {data: 'id', name: 'id', visible: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'message', name: 'message'},
            {data: 'type', name: 'type'},
            {data: 'user.first_name', name: 'user.first_name'}
         ],
   });

   $("#search-item").click( function(e){
        e.preventDefault();
        $('#notificationTable').DataTable().clear().draw();
   });

   $("#reset-item").click( function(e){
        e.preventDefault();
         var start_date = new Date();  
         start_date.setDate(start_date.getDate() - 7);
         $("#start_date").datepicker("setDate",start_date);
         var end_date = new Date();  
         $("#end_date").datepicker("setDate",end_date);
        $('#notificationTable').DataTable().clear().draw();
   });
}); 
</script>
@endpush