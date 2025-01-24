
@extends('layouts.default')
@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
@endpush
@section('content')
   @include('flash::message')
   @include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Language </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('language.index') }}">Languages</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12 pb-20 text-right"> 
            <a href="{{ route('language.create') }}" class="btn btn-success" > <i class="fa fa-plus"></i> &nbsp; Add Language </a>              
         </div>  
         <div class="col-sm-12 col-md-12"> 
            @include('master.language.table')
         </div>    
      </div>
   </div>
@stop

@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script>
 $(function () {

            var table = $('#typeTable').DataTable({
                aaSorting     : [[0, 'desc']],
                responsive: true,
                processing: true,
                pageLength: 50,    
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                serverSide: true,
                ajax          : {
                    url     : '{!! route('language.datatable') !!}',
                    dataType: 'json'
                },
                columns       : [
                    {data: 'id', name: 'id', visible: false},
                    {data: 'language', name: 'language'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                     {
                        data: 'status', name: 'status',orderable: false, searchable: false, fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                              $("a", nTd).tooltip({container: 'body'});
                        }
                     },
                    {
                        data         : 'action', name: 'action', orderable: false, searchable: false,
                        fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                            $("a", nTd).tooltip({container: 'body'});
                        }
                    }
                ],
            });
        });
</script>
@endpush