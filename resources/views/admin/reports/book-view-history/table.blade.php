
<table id="bookViewHistoryTable" class="table table-bordered table-hover" style="width:100%">
      <tr>

         <th>Book Title</th>
         <th>Call Number</th>
         <th>ISBN</th>
         <th>Total Member Taken</th>
         <th>Created At</th>
      </tr>
   
   @forelse($items as $item)
      <tr>
<!-- <td id="collapseButton" onclick="collapse(this)"><span class="btn shadow btn-primary"  >+</span></td>              -->
                  
            <td>{{ $item->title }}</td>
             <td>{{ $item->call_number }}</td>
            <td>{{ $item->isbn }}</td>
            <td>{{ $item->checkouts->count() }}</td>
            <td>{{ $item->created_at->format('d-m-Y') }}</td>
      </tr>
      <!-- @if($item->has('checkouts'))
         <div id="hidden">
            <tr style="display:none">
               <td> </td>   
               <td colspan=5> 
                  <table  class="table table-bordered table-hover">
                     <thead>
                        <tr>
                           <th>#</th>
                           <th>Created At</th>
                           <th>Member Name</th>
                           <th>Date of Return (check in)</th>
                           <th>Actual Return Date</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($item->checkouts as $checkout)
                           <tr>               
                                 <td>{{ $loop->iteration }}</td>               
                                 <td>{{ date('d-m-Y', strtotime($checkout->created_at)) }} </td>
                                 <td>{{  $checkout->user->first_name }} </td>
                                 @if(isset($checkout->created_at))
                                    <td>{{  date('d-m-Y', strtotime($checkout->created_at)) }}</td>
                                 @else
                                   <td> </td>
                                 @endif
                                 <td>{{ date('d-m-Y', strtotime($checkout->date_of_return)) }}</td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </td>   
            </tr>
         <div>
      @endif -->
      @empty
      <tr> <td colspan=5>No data available in table</td> </tr>
   @endforelse
</table>
<div class="pull-right">
{{ $items->onEachSide(5)->links() }}
</div>