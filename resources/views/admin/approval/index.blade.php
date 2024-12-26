
@extends('layouts.default')
@section('content')

@include('flash::message')
@include('flash')
            <div class="row">
                <div class="col-sm-12 col-md-6">         
                    <h3 class="text-inverse">Approval List 
                        <a class="refresh-approval" id="tooltip" title="reload table"> 
                            <span class="fa fa-refresh">  </span> 
                        </a>  
                        <a id="approve-button" class="btn btn-info bt-sm"> Approve All </a>
                    </h3>

                </div>
                <div class="col-sm-12 col-md-6">         
                 <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                         @if(Sentinel::inRole('admin'))
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                        @elseif(Sentinel::inRole('manager')) 
                        <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                        @endif
                       <li class="breadcrumb-item active" aria-current="page">Approval</li>
                    </ol>
                 </nav>
              </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12"> 
                    <div class="card">
                        <div class="row">
                            <div class="col-sm-12 col-md-12"> 
                                @include('admin.approval.table')
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
 const ApprovalAllItem = [];
 const ApprovalSelectedItem = [];
 var ApprovedItem;
 $(function () {
    
    var table = $('#manager-appoval-table').DataTable({
        aaSorting     : [[0, 'desc']],
        responsive: true,
        processing: true,
        pageLength: 50,    
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        serverSide: true,
        searching: false, paging: true,info: true,
        bSort: false,
        ajax          : {
            url     : '{!! route('approval-list.datatable') !!}',
            dataType: 'json',
            data:   function(d){
                d.search_item_name = $("#search_item_name").val();
                d.type = $("#type").val();
                d.genre = $("#genre").val();
                d.category = $("#category").val();
                d.subcategory = $("#subcategory").val();
            }
        },
        columns       : [
            {data: 'id', name: 'id', visible: false},
            {
                data: 'approval_item', name: 'approval_item'
            },
            {
                data: 'item_id', name: 'item.item_id'
            },
            {
                data: 'item_name', name: 'item.item_name'
            },
            {
                data: 'requested_by', name: 'user.full_name'
            },
            {
                data: 'created_at', name: 'created_at'
            },
            {
                data: 'category', name: 'categories.category_name'
            },
            {
                data: 'subcategory', name: 'subcategory.subcategory_name'
            },
            {
                data: 'genre', name: 'genre.genre_name'
            },
            {
                data: 'is_issued', name: 'is_issued'
            },
            {
                data         : 'action', name: 'action', orderable: false, searchable: false,
                fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                    //  console.log( nTd );
                    $("a", nTd).tooltip({container: 'body'});
                }
            } 
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex ) {
            ApprovalAllItem.push(aData.id);
        },
        drawCallback: function( settings ) {
            ApprovalSelectedItem.map(e => {
                return $(`[data-approval-id=${e}]`).prop("checked", true);
            });
        },

    });

    $(".refresh-approval").click(function(){
        $('#manager-appoval-table').DataTable().clear().draw();
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
                    $('#manager-appoval-table').DataTable().clear().draw();
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

    $(document).on('click','.approval-all-item', function(){
        if($(this).is(':checked')) {
            $(".approval-item").prop('checked',true);
        } else {
            ApprovalAllItem.length = 0;
            ApprovalSelectedItem.length = 0;
            $(".approval-item").prop('checked',false);
        }
    });

    $(document).on('click','.approval-item', function(){
        if($(this).is(':checked')) {
            $(this).prop('checked',true);
            ApprovalSelectedItem.push($(this).data('approval-id'));
        } else {
            $(this).prop('checked',false);
            const index = ApprovalSelectedItem.indexOf($(this).data('approval-id'));
            if (index > -1) {
              ApprovalSelectedItem.splice(index, 1);
            }
        }
    });

    $("#approve-button").click(function(e) {
        e.preventDefault();
        if( $(".approval-all-item").is(':checked') == true ) {
            ApprovedItem =  ApprovalAllItem;
        } else {
             ApprovedItem =  ApprovalSelectedItem;
        }
        if(ApprovedItem.length <= 0) {
            errorAlert('Please select any item');
            return false;
        }
        $.ajax({
            url: '{{ route('approval-list.approve-all-request') }}',  
            type: 'POST',
            data: {id: ApprovedItem},
            success : function(res) {
               if(res.status) {
                    $('#manager-appoval-table').DataTable().clear().draw();
                    $("#success-message").html(res.msg);
                    $("#success-modal").modal('show');
                    ApprovalSelectedItem.length = 0;
                    ApprovalAllItem.length = 0;
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
            
});
</script>
@endpush

     
