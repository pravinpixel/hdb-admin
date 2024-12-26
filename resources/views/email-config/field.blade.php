<div class="container"> 
    <div class="col-md-6">
        <div class="form-group">
            <label for="type">@lang('email.driver')</label>
            {!! Form::text('driver', null, ['class' => 'form-control', 'placeholder' => 'Type here..!', 'id' => 'driver', 'required' => true]) !!}
        </div>
        <div class="form-group">
            <label for="type">@lang('email.host'):</label>
            {!! Form::text('host', null, ['class' => 'form-control', 'placeholder' => 'Type here..!', 'id' => 'host', 'required' => true]) !!}
        </div>
        <div class="form-group">
            <label for="type">@lang('email.port')</label>
            {!! Form::text('port', null, ['class' => 'form-control',  'placeholder' => 'Type here..!', 'id' => 'port', 'required' => true]) !!}
        </div>

        <div class="form-group">
            <label for="type">@lang('email.encryption')</label>
            {!! Form::text('encryption', null, ['class' => 'form-control', 'placeholder' => 'Type here..!', 'id' => 'encryption', 'required' => true]) !!}
        </div>
        <div class="form-group">
            <label for="type">@lang('email.user-name'):</label>
            {!! Form::text('user_name', null, ['class' => 'form-control','placeholder' => 'Type here..!',  'id' => 'user_name','required' => true]) !!}
        </div>
        <div class="form-group">
            <label for="type">@lang('email.password')</label>
           
            <div  style="display: flex; align-items:center;jsutify-content:space-between; ">
                {!! Form::text('password', '', ['class' => 'form-control', 'placeholder' => 'Type here..!', 'id' => 'password', 'required' => true]) !!}
                {!! Form::text('generate-password', null, ['class' => 'form-control ','style'=>'margin-left:10px', 'id' => 'generate-password' ,'disabled'=>true]) !!}   
                <div class="pull-right" style="margin-left:10px;cursor: pointer;" >
                    <button class="btn btn-info btn-sm" onclick="generatePassword(event)"><i class="fa fa-refresh" id="tooltip" data-toggle="tooltip" data-placement="top" title="generate password"></i> Generate</button>
                </div>
            </div>
   
        </div>
        <div class="form-group">
            <label for="type">@lang('email.sender-name')</label>
            {!! Form::text('sender_name', null, ['class' => 'form-control', 'id' => 'sender_name', 'required' => true]) !!}
        </div>
        <div class="form-group">
            <label for="type">@lang('email.sender-email')</label>
            {!! Form::text('sender_email', null, ['class' => 'form-control', 'id' => 'sender_email', 'required' => true]) !!}
        </div>

        <div class="form-group">
            <button class="btn btn-success waves-effect waves-light" id="email-config-save" type="submit">Save</button>
            <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
        </div>
    </div>
</div>




