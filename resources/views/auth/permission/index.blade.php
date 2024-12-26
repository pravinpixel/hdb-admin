
@extends('layouts.default')

@push('page_css')

@endpush

@section('content')

    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Permissions </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(Sentinel::inRole('admin'))  
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                @elseif(Sentinel::inRole('manager')) 
                  <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                @endif
                <li class="active">Permissions</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
         {!! Form::select('size', $roles, null, ['placeholder' => 'Select Role', 'class' => 'form-control', 'id' => 'role_id']) !!}
      <div class="container">
           {!! Form::open(['route' => 'permission.store',"id" => "permissionForm", "Method" => "POST", "class" => "cmxform form-horizontal tasi-form"])!!}
                <input type="hidden" name="selected_role_id" id="selected_role_id"> 
                <div id="append-permission">  </div>
            {!! Form::close()!!}  
        </div>
   </div>
@endsection

@push('page_script')
 <script>
    
    $('#role_id').change(function(e) {
        let id = $(this).val();
        $("#selected_role_id").val(id);
        $.ajax({
            url : '{{ route('permission.get-permission') }}',
            type: 'GET',
            data: {id: id},
            success : function(res) {
               $("#append-permission").html(res);
            },
            error : function(e) {
                console.log(`per ${e}`);
            }
        });
    });

    $("#permissionForm").submit(function(e) {
        e.preventDefault();
        $.ajax({
            url : '{{ route('permission.store') }}',
            type: 'POST',
            data: $("#permissionForm").serialize(),
            success : function(res) {
               $("#append-permission").html(res);
               $("#success-message").html("Permission updated successfully");
               $("#success-modal").modal('show');
            },
            error : function(e) {
                $("#error-message").html("Something went wrong try again");
                $("#error-modal").modal('show');
                console.log(`per ${e}`);
            }
        });
    });
        
 </script>
@endpush