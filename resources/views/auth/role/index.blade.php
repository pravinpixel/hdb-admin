
@extends('layouts.default')

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
@endpush

@section('content')
    @include('flash::message')
    @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Roles </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                @elseif(Sentinel::inRole('manager')) 
                <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                @endif
               <li class="breadcrumb-item active"><a href="{{route('role.index')}}">Roles</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
        <div class="col-sm-12 col-md-12 pb-20 text-right">               
        <a href="{{ route('role.create') }}" class="btn btn-info"> Add Role </a>       
        </div>  
        <div class="col-sm-12 col-md-12"> 
            @include('auth.role.table')
        </div>    
      </div>
   </div>
@endsection
@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
 $(function () {
    $("#roles-table").DataTable({
        responsive: true,
        processing: true,
        pageLength: 50,    
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        serverSide: true,
        order: [[ 0, "desc" ]],
        ajax : {
            url: '{!! route('role.datatable') !!}',
            dataType: 'json'
        },
        columns: [
            {data: 'id', name: 'id', visible: false},
            {data: 'name', name: 'name'},
            {
            data: 'action', name: 'action', orderable: false, searchable: false,
            fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                $("a", nTd).tooltip({container: 'body'});
            }
            }
        ],
    });
 });
</script>
@endpush

     
