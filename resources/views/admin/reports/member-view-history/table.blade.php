<table id="memberViewHistoryTable" class="table table-bordered table-hover" style="width:100%">
         <tr>
            <th>#</th>
            <th>Member ID</th>
            <th>Member Name</th>
            <th>Role Name</th>
            <th>Total Item Taken</th>
            <th>Created At</th>
         </tr>
      @forelse($members as $member)
         <tr>
              <td id="collapseButton" onclick="collapse(this)"><span class="btn shadow btn-primary"  >+</span></td>           
              <td>{{$member->member_id}}</td>
              <td>{{$member->first_name}}  {{$member->first_name}}</td>
              <td>{{$member->roles->first()->name}}</td>
              <td>{{$member->checkouts()->count()}}</td>
              <td>{{$member->created_at->format('d-m-Y')}}</td>
         </tr>
         @if($member->has('checkouts'))
            <div class="hidden">
               <tr style="display:none">
                  <td></td>
                  <td colspan=5> 
                     <table class="table table-bordered table-hover">
                        <thead>
                           <tr>
                              <th>#</th>
                              <th>Created At</th>
                              <th>Item Name</th>
                              <th>Date of Return (check in)</th>
                              <th>Date of Return</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($member->checkouts as $checkout)
                              <tr>           
                                    <td>{{ $loop->iteration }}</td>               
                                    <td>{{ date('d-m-Y', strtotime($checkout->created_at)) }} </td>
                                    <td>{{  $checkout->item->item_name ?? '' }} </td>
                                    @if(isset($checkout->checkIn->created_at))
                                       <td>{{  date('d-m-Y', strtotime($checkout->checkIn->created_at)) }}</td>
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
         @endif
      @empty
      <tr> <td colspan=5>No data available in table</td> </tr>
   @endforelse
</table>
<div class="pull-right">
{{ $members->onEachSide(5)->links() }}
</div>