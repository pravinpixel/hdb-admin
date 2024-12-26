@component('mail::message')
# {{$details['title']}}  

Dear {{$details['user']->first_name}}, <br>
Your Password is reset and here the new password is shared below  <br>
New password: {{$details['password']}} <br>

@component('mail::button', ['url' => $details['url']])
click here to login
@endcomponent

Thanks,<br>
{{ config('email.email_regards') }}
<br>
{{ config('email.email_footer') }}
@endcomponent
