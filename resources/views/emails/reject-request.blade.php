@component('mail::message')
# {{ $details['title'] }}

Dear {{$details['user']->first_name}},  <br>
Your request is Reject by {{$details['manager']->first_name}} {{$details['manager']->last_name}}<br>

#  Item Details: <br>
Item Name: {{ $details['item']->item_name}} <br>
Item ID: {{ $details['item']->item_id}} <br><br>

Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent