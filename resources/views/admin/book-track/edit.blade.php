@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark">Edit</h2>
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
               <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
               <input type="text" class="form-control"  value="{{$item->item->item_ref}}" readonly>              
            </div>
         </div>
         <div class="form-group">
            {!! Form::label('first_name', 'Book Name', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="text" class="form-control"  value="{{$item->title}}" readonly>              
            </div>
         </div>
          <div class="form-group">
            {!! Form::label('first_name', 'Staff Name', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="text" class="form-control"  value="{{$item->user->first_name}}" readonly>              
            </div>
         </div>
          <div class="form-group">
            {!! Form::label('first_name', 'CheckIn Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10">
               <input type="date" name="checkin_date" class="form-control" value="{{$item->date}}" >   
         @error('checkin_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror           
            </div>
         </div>
          <div class="form-group" >
            {!! Form::label('first_name', 'CheckOut Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10" >
               <input type="date" name="checkout_date" class="form-control" value="{{$item->checkout_date}}">
               @error('checkout_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror                
            </div>
         </div>
           <div class="form-group" >
            {!! Form::label('first_name', 'Due Date', ['class' => 'control-label col-lg-2'], false) !!}
            <div class="col-lg-10" >
            <input type="date" name="due_date" class="form-control" id="date_of_return" value="{{$item->date_of_return}}" required>  
             @error('due_date')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror            
            </div>
         </div>
         <input type="hidden" name="status" class="form-control" value="{{$item->status}}">  
         <div  class="form-group" >
         <div class="col-lg-offset-2 col-lg-10" style="margin-top: 20px !important; ">
            <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
            <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
          </div>
          </div>
        </form>
          </div>
         </div>
         </div>
         </div>
   </div>
 
@stop
@push('page_script')
<script>
    // Get the date of return from Laravel Blade
    const dateOfReturn = "{{ $item->date_of_return }}";

    // Create a Date object from the date of return
    const returnDate = new Date(dateOfReturn);

    // Add 21 days to the return date to get the max date
    const maxDate = new Date(returnDate);
    maxDate.setDate(returnDate.getDate() + 21);  // Adding 21 days

    // Format the dates to YYYY-MM-DD format (this is required by the input[type="date"])
    const formattedReturnDate = returnDate.toISOString().split('T')[0];
    const formattedMaxDate = maxDate.toISOString().split('T')[0];

    // Define the disabled date range (5th to 8th January 2025)
    const disabledStartDate = new Date(dateOfReturn);
    const disabledEndDate = new Date();
    const formattedDisabledStartDate = disabledStartDate.toISOString().split('T')[0];
    const formattedDisabledEndDate = disabledEndDate.toISOString().split('T')[0];

    // Set the min and max attributes of the input field
    const dateInput = document.getElementById('date_of_return');
    dateInput.setAttribute('min', formattedReturnDate); // Disable all previous dates
    dateInput.setAttribute('max', formattedMaxDate);   // Disable dates beyond 21 days after the date of return

    // Disable the date range from 5th to 8th January 2025
    dateInput.addEventListener('input', function () {
        const selectedDate = new Date(dateInput.value);
        if (selectedDate >= disabledStartDate && selectedDate <= disabledEndDate) {
            // If the selected date is between 5th and 8th January 2025, reset the input
            dateInput.value = ''; // Reset the date input
        }
    });

</script>
@endpush