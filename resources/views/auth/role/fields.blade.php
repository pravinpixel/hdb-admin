<div class="form-group">
    {!! Form::label('role_name', 'Role Name *', ['class' => 'control-label col-lg-2']) !!}
    <div class="col-lg-10">
        {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name']) !!}
    </div>
</div>

<div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
        <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
        <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
    </div>
</div>