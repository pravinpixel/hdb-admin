@extends('layouts.default')
@section('content')
@include('flash::message')
@include('flash')
   <div class="row">
      <div class="col-sm-12 col-md-12">         
         <h2 class="text-dark"> Dashboard </h2>
      </div>
   </div>
   <div class="row">
      <div class="col-sm-12 col-md-4">
         <div class="card">
            <div class="row">
               <div class="col-6">
                  <h1>Total Items</h1>
               </div>
               <div class="col-6">
                  <h5>12324</h5>
               </div>
            </div>
         </div>
      </div>
   </div>
@stop