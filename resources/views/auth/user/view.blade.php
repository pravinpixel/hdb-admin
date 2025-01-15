
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
               <li class="breadcrumb-item"><a href="{{route('user.index')}}">Admins</a></li>
               <li class="breadcrumb-item active" aria-current="page"><a href="">View</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
               <div class="form">
                    {!! Form::model($user,['route' => ['user.index',],"id" => "", "method" => "", "class" => "cmxform form-horizontal tasi-form"]) !!}
                     
<div class="form-group">
    {!! Form::label('member_id', 'Member ID ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('member_id', $member_id , ['class' => 'form-control', 'readonly' => 'readonly']) !!}
    </div>
</div>
 <div class="form-group @if($errors->has('role')) has-error @endif">
    {!! Form::label('role', 'Role ', ['class' => 'control-label col-lg-2'], false) !!}
     <div class="col-lg-10">
        {!! Form::text('role',$user->roles[0]->name, ['class' =>'form-control','readonly' => 'readonly'])  !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('first_name', 'Firstname ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name','readonly' => 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('last_name', 'Lastname ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name','readonly' => 'readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email ', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email','readonly' => 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('mobile', 'Mobile', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('mobile', null, ['class' => 'form-control',  'onkeypress' => 'javascript:return isNumber(event)', 'id' => 'mobile','readonly' => 'readonly']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('address', 'Address', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address','readonly' => 'readonly']) !!}
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
<script type="text/javascript" src="{{ asset('assets/pages/user.js') }}" ></script>
@endpush

     
