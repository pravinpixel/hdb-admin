@extends('layouts.default')
@section('content')
   @include('flash::message')
   @include('flash')
    <div class="row">
      <div class="col-sm-12 col-md-6">         
         <h2 class="text-dark">Edit Book Track </h2>
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
         <div class="form row">
         <form action="{{ route('book-track.update', $item->id) }}" method="post">
         @csrf
         <div class="form-group col-md-6">
                        <label for="end">Due Date :</label>
                            <input type="date" name="date_of_return" class="form-control" id="date_of_return" value="{{$item->date_of_return}}">
                            
               </div>
          </div>
          <button type="submit" class="btn btn-primary" >Submit</button>
          </form>
         
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
    maxDate.setDate(returnDate.getDate() + 7);  // Adding 21 days

    // Format the dates to YYYY-MM-DD format (this is required by the input[type="date"])
    const formattedReturnDate = returnDate.toISOString().split('T')[0];
    const formattedMaxDate = maxDate.toISOString().split('T')[0];

    // Set the min and max attributes of the input field
    const dateInput = document.getElementById('date_of_return');
    dateInput.setAttribute('min', formattedReturnDate); // Disable all previous dates
    dateInput.setAttribute('max', formattedMaxDate);   // Disable dates beyond 21 days after the date of return
</script>
 @endpush