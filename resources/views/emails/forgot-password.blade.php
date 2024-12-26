@component('mail::message')
# {{ $details['title'] }}

Dear Admin,  <br>
Forgot password request is given by {{$details['user']->first_name}}. Request you reset it for the below user
<br>
MemberID: {{ $details['user']->member_id}}  <br>
Name: {{ $details['user']->first_name}}  {{ $details['user']->last_name}} <br>
Role: {{ $details['user']->roles[0]->name}}  <br>

@component('mail::button', ['url' => $details['url']])
Reset
@endcomponent

Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent