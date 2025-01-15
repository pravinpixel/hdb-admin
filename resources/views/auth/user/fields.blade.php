
<div class="form-group">
    {!! Form::label('member_id', 'Member ID <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('member_id', $member_id , ['class' => 'form-control', 'readonly' => 'readonly']) !!}
    </div>
</div>
 <div class="form-group @if($errors->has('role')) has-error @endif">
    {!! Form::label('role', 'Role <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
     <div class="col-lg-10">
        {!! Form::select('role',$roleDb , $userRole, ['class' =>'form-control', 'placeholder' => 'select role'])  !!}
          @error('role')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>
<div class="form-group">
    {!! Form::label('first_name', 'Firstname <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name']) !!}
         @error('first_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>
<div class="form-group">
    {!! Form::label('last_name', 'Lastname <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name']) !!}
         @error('last_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
         @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>

<div class="form-group">
    {!! Form::label('mobile', 'Mobile', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('mobile', null, ['class' => 'form-control',  'onkeypress' => 'javascript:return isNumber(event)', 'id' => 'mobile']) !!}
         @error('mobile')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>

<div class="form-group">
    {!! Form::label('address', 'Address', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::textarea('address', null, ['class' => 'form-control', 'id' => 'address']) !!}
         @error('address')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>
<div class="form-group">
    {!! Form::label('password', 'Password', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
         @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>
<div class="form-group">
    {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::password('password_confirmation',  ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
         @error('password_confirmation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror 
    </div>
</div>


<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
        <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
    </div>
</div>
