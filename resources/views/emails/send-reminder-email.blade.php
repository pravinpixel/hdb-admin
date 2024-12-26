@component('mail::message')
# {{ $details['title'] }} 

Dear {{ $details['user']->first_name}}, <br>
Your item is overdue for {{ $details['overdue']}} days. Request you to return back the item {{ $details['item']->item_name}} <br>
Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent