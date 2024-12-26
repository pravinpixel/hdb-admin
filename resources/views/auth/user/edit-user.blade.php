
@extends('layouts.default')

@push('page_css')

@endpush

@section('content')
@include('flash::message')
@include('flash')
  <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark"> Edit Profile </h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
                  <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item"><a href="{{route('user.index')}}">Users</a></li>
               <li class="breadcrumb-item active" aria-current="page"><a href="">Edit</a></li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
      <div class="row">
         <div class="col-sm-12 col-md-12"> 
               <div class="form">
                    {!! Form::model($user,['route' => ['user.update-profile', $user->id],"id" => "userProfileFormEdit", "method" => "put", "class" => "cmxform form-horizontal tasi-form"]) !!}
                        <div class="form-group">
                            {!! Form::label('member_id', 'Member ID*', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::text('member_id', null, ['class' => 'form-control', 'disabled'=> 'disabled', 'id' => 'member_id']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('first_name', 'Firstname *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('last_name', 'Lastname *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::text('last_name', null, ['class' => 'form-control',  'id' => 'last_name']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('email', 'Email *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::email('email', null, ['class' => 'form-control', 'disabled'=> 'disabled','id' => 'email']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('mobile', 'Mobile *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::text('mobile', null, ['class' => 'form-control', 'disabled'=> 'disabled',  'onkeypress' => 'javascript:return isNumber(event)', 'id' => 'mobile']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('address', 'Address *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::textarea('address', null, ['class' => 'form-control',  'id' => 'address']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('password', 'Old Password *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::password('old_password', ['class' => 'form-control', 'id' => 'old_password']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('password', 'New Password *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::password('new_password', ['class' => 'form-control', 'id' => 'new_password']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('password_confirmation', 'Confirm Password *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::password('password_confirmation',  ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
                            </div>
                        </div>
                        <div class="form-group @if($errors->has('role')) has-error @endif">
                            {!! Form::label('role', 'Role *', ['class' => 'control-label col-lg-2']) !!}
                            <div class="col-lg-10">
                                {!! Form::select('',$roleDb , $userRole, ['class' =>'form-control', 'disabled'=> 'disabled', 'placeholder' => 'select role'])  !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-10">
                                <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
                                <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
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
    
         