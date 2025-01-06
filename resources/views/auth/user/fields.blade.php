
<div class="form-group">
    {!! Form::label('member_id', 'Member ID*', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('member_id', $member_id , ['class' => 'form-control', 'readonly' => 'readonly']) !!}
    </div>
</div>
 <div class="form-group @if($errors->has('role')) has-error @endif">
    {!! Form::label('role', 'Role *', ['class' => 'control-label col-lg-2']) !!}
     <div class="col-lg-10">
        {!! Form::select('role',$roleDb , $userRole, ['class' =>'form-control', 'placeholder' => 'select role'])  !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('first_name', 'Firstname *', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('last_name', 'Lastname *', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email *', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('mobile', 'Mobile', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('mobile', null, ['class' => 'form-control',  'onkeypress' => 'javascript:return isNumber(event)', 'id' => 'mobile']) !!}
    </div>
</div>

<div class="form-group">
    {!! Form::label('address', 'Address', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('password', 'Password', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::password('password_confirmation',  ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
    </div>
</div>


<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
        <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
    </div>
</div>
