<!-- @if($errors->all())
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
        <strong>{{ collect($errors->all(':message'))->first() }}</strong>
    </div>
@endif
$errors->all()  validator error only throw this method -->