@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark">View</h2>
      </div>
      <div class="col-sm-12 col-md-6">         
         <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
               @if(Sentinel::inRole('admin'))
               <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
               @elseif(Sentinel::inRole('manager')) 
               <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
               @endif
               <li class="breadcrumb-item"><a href="{{ route('book-track.index') }}">Book Track</a></li>
               <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
         </nav>
      </div>
   </div>
   <div class="card">
         <div class="row">
         <div class="col-sm-12 col-md-12"> 
          <div class="form">
         <form action="{{ route('book-track.update', $item->id) }}" method="post" class="cmxform form-horizontal tasi-form">
         @csrf
           <div class="form-group">
            {!! Form::label('first_name', 'RFID', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="text" class="form-control" id="date_of_return" value="{{$item->item->item_ref}}" readonly>              
            </div>
         </div>
         <div class="form-group">
            {!! Form::label('first_name', 'Book Name', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="text" class="form-control" id="date_of_return" value="{{$item->title}}" readonly>              
            </div>
         </div>
          <div class="form-group">
            {!! Form::label('first_name', 'Staff Name', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="text" class="form-control" id="date_of_return" value="{{$item->user->first_name}}" readonly>              
            </div>
         </div>
          <div class="form-group">
            {!! Form::label('first_name', 'CheckIn Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="date" name="date" class="form-control" value="{{$item->date}}" readonly>              
            </div>
         </div>
          <div class="form-group" >
            {!! Form::label('first_name', 'CheckOut Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10" >
               <input type="date" name="checkout_date" class="form-control" value="{{$item->checkout_date}}" readonly>              
            </div>
         </div>
           <div class="form-group" >
            {!! Form::label('first_name', 'Due Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10" >
            <input type="date" name="date_of_return" class="form-control" id="date_of_return" value="{{$item->date_of_return}}" readonly>             
            </div>
         </div>
        </form>
          </div>
         </div>
         </div>
         </div>
   </div>
 
@stop
