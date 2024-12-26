
        <li class="text-center notifi-title">Notification</li>
        @forelse($notifications as $key => $notification)
            <li class="list-group">
                <!-- list item-->
                <a href="javascript:void(0);" class="list-group-item">
                <div class="media">
                    <div class="pull-left">
                    <em class="text-info"> {{  Str::replace('_', ' ', $notification->type) }} </em>
                    </div>
                    <div class="media-body clearfix">
                    <div class="media-heading"> {{ $notification->message }}</div>
                    <p class="m-0">
                        <small> {{ $notification->created_at }}</small>
                    </p>
                    </div>
                </div>
                </a>
            </li>
         @empty
         <li class="list-group">
            <!-- list item-->
            <a href="javascript:void(0);" class="list-group-item">
            <div class="text-center">
                <span class="text-info">Notification not available</span>
            </div>
            </a>
        </li>
        @endforelse
</li>  