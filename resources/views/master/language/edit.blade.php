
@extends('layouts.default')
@section('content')
@include('flash::message')
@include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Edit </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item "><a href="{{ route('language.index') }}">Languages</a></li>
               <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="form">
         {!!  Form::model($result, ['route' => ['language.update', $result->id], 'method' => 'put',  'class' => ' pad-10', 'id' => 'typeForm']) !!}
            @include('master.language.fields')
            <input type="submit" class="btn btn-success" value="@lang('global.update')">
         {!! Form::close() !!}
      </div>
   </div>
@stop

@push('page_script')
<script src="{{ asset('assets/pages/language.js') }}"></script>
@endpush