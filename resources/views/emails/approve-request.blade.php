@component('mail::message')
# {{ $details['title'] }}

Dear Manager,  <br>
The Approve request is requested by {{$details['user']->first_name}}. Request you validate and confirm to the below user for the item {{$details['item']->item_name}}
<br>
MemberID: {{ $details['user']->member_id }} <br>
Name: {{ $details['user']->first_name }}  {{ $details['user']->last_name }}   <br>
Role: {{ $details['user']->roles[0]->name }}  <br>

@component('mail::button', ['url' => $details['url']])
Approve
@endcomponent

Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent
