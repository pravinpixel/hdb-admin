
@extends('layouts.default')

@push('page_css')

@endpush

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
               <li class="breadcrumb-item"><a href="{{route('staff.index')}}">Staffs</a></li>
               <li class="breadcrumb-item active" aria-current="page"><a href="">Edit</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
               <div class="form">
                    {!! Form::model($user,['route' => ['staff.update', $user->id],"id" => "staffFormEdit", "method" => "put", "class" => "cmxform form-horizontal tasi-form"]) !!}
                        @include('auth.staff.fields')
                    {!! Form::close() !!}
                </div> <!-- .form -->
         </div>    
      </div>
   </div>
@endsection
@push('page_script')
<script type="text/javascript" src="{{ asset('dark/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/pages/staff.js') }}" ></script>
@endpush

     
