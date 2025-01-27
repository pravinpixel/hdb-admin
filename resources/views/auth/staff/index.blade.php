
@extends('layouts.default')

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
@endpush

@section('content')
@include('flash::message')
@include('flash')
    <div class="row">
        <div class="col-sm-12 col-md-6">         
            <h2 class="text-dark"> Staffs </h2>
        </div>
        <div class="col-sm-12 col-md-6">         
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(Sentinel::inRole('admin'))
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                @elseif(Sentinel::inRole('manager')) 
                <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('staff.index')}}">Staffs </a></li>
            </ol>
            </nav>
        </div>
    </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12 pb-20 text-right">               
          <a href="{{ route('staff.create') }}" class="btn btn-info"> Add Staff</a>        
         </div>  
         <div class="col-sm-12 col-md-12"> 
               @include('auth.staff.table')
         </div>    
      </div>
   </div>
@endsection
@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
 $(function () {

            var table = $('#staffs-table').DataTable({
                aaSorting     : [[0, 'desc']],
                responsive: true,
                processing: true,
                pageLength: 50,    
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                serverSide: true,
                ajax          : {
                    url     : '{!! route('staff.datatable') !!}',
                    dataType: 'json'
                },
                columns       : [
                    {data: 'id', name: 'users.id', visible: false},
                    {
                        data:'member_id', name: 'member_id',
                    },
                    {
                        data: 'first_name', name: 'first_name'
                    },
                    // {
                    //     data: 'last_name', name: 'last_name'
                    // },
                    {data: 'designation', name: 'designation'},
                    {data: 'group', name: 'group'},
                    {data: 'email', name: 'email'},
                    {
                        data: 'status', name: 'status', orderable: false, searchable: false, fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            //  console.log( 'checl',nTd );
                            $("a", nTd).tooltip({container: 'body'});
                        }
                    },
                    {data: 'created_at', name: 'created_at', visible: false},
                    {data: 'updated_at', name: 'updated_at', visible: false},
                    {
                        data         : 'action', name: 'action', orderable: false, searchable: false,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            //  console.log( nTd );
                            $("a", nTd).tooltip({container: 'body'});
                        }
                    }
                ],
            });
});
</script>
@endpush

     
