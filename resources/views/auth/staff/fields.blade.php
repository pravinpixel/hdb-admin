
<div class="form-group">
    {!! Form::label('member_id', 'Staff No <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('member_id',null, ['class' => 'form-control','id' => 'member_id']) !!}
         @error('member_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div>
<div class="form-group">
    {!! Form::label('first_name', 'Name <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('first_name', null, ['class' => 'form-control', 'id' => 'first_name']) !!}
         @error('first_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div>
<!--div class="form-group">
    {!! Form::label('last_name', 'Lastname *', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('last_name', null, ['class' => 'form-control', 'id' => 'last_name']) !!}
         @error('last_name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div-->
<div class="form-group">
    {!! Form::label('designation', 'Designation <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('designation', null, ['class' => 'form-control', 'id' => 'designation']) !!}
         @error('designation')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div>
<div class="form-group">
    {!! Form::label('group', 'Orgn/Group <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::text('group', null, ['class' => 'form-control', 'id' => 'group']) !!}
         @error('group')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div>
<div class="form-group">
    {!! Form::label('email', 'Email Address <span style="color: red">*</span>', ['class' => 'control-label col-lg-2'], false) !!}
    <div class="col-lg-10">
        {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email']) !!}
         @error('email')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    </div>
</div>

<input type="hidden" name="role" value="7">
<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
        <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
    </div>
</div>
