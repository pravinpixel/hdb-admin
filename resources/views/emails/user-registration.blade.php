@component('mail::message')
# {{ $details['title'] }}

Dear {{$details['user']->first_name}}, <br>
You had registered with our {{ config('app.name') }} Here is your credentials with which you can login into the system <br> 

MemberID: {{$details['user']->member_id}} <br>
Name: {{$details['user']->first_name}} {{$details['user']->last_name}} <br>
Role: {{$details['role']->name ?? ''}} <br>
Email: {{$details['user']->email}}  <br>
Password: {{$details['password']}} <br>
@component('mail::button', ['url' => $details['url']])
click here to login
@endcomponent

Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent