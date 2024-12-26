
@extends('layouts.default')

@push('page_css')

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
               <li class="breadcrumb-item" aria-current="page"><a href="#">Create</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
            <div class="form">
                {!! Form::open(['route' => 'role.store',"id" => "roleForm", "Method" => "POST", "class" => "cmxform form-horizontal tasi-form"]) !!}
                    @include('auth.role.fields')
                {!! Form::close() !!}
            </div> <!-- .form -->
         </div>    
      </div>
   </div>
@endsection
@push('page_script')
<script type="text/javascript" src="{{ asset('dark/assets/plugins/jquery-validation/dist/jquery.validate.min.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/pages/role.js') }}" ></script>
@endpush

     
