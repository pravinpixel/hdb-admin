@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Create </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item"><a href="{{ route('item.index') }}">Items</a></li>
               <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
         <div class="form">
            {!! Form::open(['route' => 'item.store', 'method' => 'post',  'class' => 'pad-10', 'id' => 'itemForm', 'enctype'=>"multipart/form-data"]) !!}
               @include('master.item.fields')
               <input type="submit" class="btn btn-success" value="@lang('global.save')">
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
});
</script>
 @endpush