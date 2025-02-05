@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div class="alert alert-dismissible 
                    alert-{{ $message['level'] }}
                    {{ $message['important'] ? 'alert-important' : '' }}"
                    role="alert"
        >
            
                <button type="button"
                        class="close"
                        style="opacity: 1 !important"
                        data-dismiss="alert"
                        aria-hidden="true"
                >&times;</button>
           

            {!! $message['message'] !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
