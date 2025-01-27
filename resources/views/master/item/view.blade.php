@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> View Book </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item"><a href="{{ route('item.index') }}">Books</a></li>
               <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
         <div class="form">
            {!! Form::model($result, ['route' => ['item.update', $result->id], 'method' => 'put',  'class' => 'pad-10', 'id' => 'itemEditForm', 'enctype'=>"multipart/form-data"]) !!}
              <div class="row">  
    <div class="col-lg-6">
       
        <div class="form-group">
            <label for="title">Title: </label>
            {!! Form::text('title', null , ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
        <div class="form-group">
            <label for="author">Author: </label>
            {!! Form::text('author', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
        <div class="form-group">
            <label for="barcode">Acession/Barcode Number:</label>
             {!! Form::text('barcode', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
        <div class="form-group">
            <label for="rfid">RFID:</label>
             {!! Form::text('item_ref', null , ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
        
          <div class="form-group">
            <label for="Langugae">Langugae:</label>
             {!! Form::text('language',$result->language['language'] ??'', ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
         <div class="form-group">
            <label for="status">Status</label>
        <!-- {!! Form::checkbox('status', null, false, ['class' => '', 'id' => 'status','readonly' => 'readonly'])  !!} -->
            {!! Form::radio('status', 'active', false, ['class' => '', 'id' => 'active'])  !!}
            <label for="status">Active</label>

            {!! Form::radio('status', 'inactive', true, ['class' => '', 'id' => 'inactive'])  !!}
            <label for="status">Inactive</label>
        </div>

    </div>


    <div class="col-lg-6">
     <div class="form-group">
            <label for="isbn">ISBN: </label>
            {!! Form::text('isbn', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
         <div class="form-group">
            <label for="call_number">Call Number:</label>
             {!! Form::text('call_number', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div>
         <div class="form-group">
            <label for="subject">Subject:</label>
           {!! Form::text('subject', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
    
        </div>
      
  <!-- <div class="form-group">
            <label for="location">Location:</label>
           {!! Form::text('location', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div> -->
         <!-- <div class="form-group">
            <label for="location">Due Period:</label>
           {!! Form::text('due_period', null, ['class' => 'form-control','readonly' => 'readonly']) !!}
        </div> -->
        
    </div>
</div>
            {!! Form::close() !!}
         </div>
   </div>
@stop

@push('page_css')
   <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
@endpush

@push('page_script')
   <script type="text/javascript" src="{{ asset('dark/assets/plugins/select2/dist/js/select2.min.js')}}"> </script>
   <script src="{{ asset('assets/pages/item.js') }}"></script>
<script>
$(document).ready(function(){
   $("#language_id").select2({
      ajax: {
         url : '{!! route('language.get-dropdown') !!}',
         data: function (params) {
            return { q: params.term }
         },
         processResults: function (data) {
            return { results: data };
         }
      }
   });
let status = {!!  $result->status !!};
let statusStr = status ? 'active' : 'inactive';
$("input[name='status'][value='" + statusStr + "']").prop('checked', true);
// if(status) {
//    $("#status").prop('checked', true);
// } else {
//    $("#status").prop('checked', false);
// }
let type = {!! json_encode($result->language->toArray()) !!}
var typeOption = new Option(type.language, type.id, true, true);
$('#language_id').append(typeOption).trigger('change'); 

});
</script>
 @endpush