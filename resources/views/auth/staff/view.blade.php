
@extends('layouts.default')

@push('page_css')

@endpush

@section('content')
@include('flash::message')
@include('flash')
  <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> View </h2>
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
               <li class="breadcrumb-item active" aria-current="page"><a href="">View</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
               <div class="form">
                    {!! Form::model($user,['route' => ['staff.update', $user->id],"id" => "staffFormEdit", "method" => "put", "class" => "cmxform form-horizontal tasi-form"]) !!}
                     
<div class="form-group">
    {!! Form::label('member_id', 'Staff No ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('member_id',null, ['class' => 'form-control','id' => 'member_id','readonly' => 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('first_name', 'Name ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name','readonly' => 'readonly']) !!}
    </div>
</div>
<!--div class="form-group">
    {!! Form::label('last_name', 'Lastname', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name','readonly' => 'readonly']) !!}
    </div>
</div-->
<div class="form-group">
    {!! Form::label('designation', 'Designation ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('designation', null, ['class' => 'form-control', 'id' => 'designation','readonly' => 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('group', 'Orgn/Group ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('group', null, ['class' => 'form-control', 'id' => 'group','readonly' => 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email Address ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email','readonly' => 'readonly']) !!}
    </div>
</div>


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

     
