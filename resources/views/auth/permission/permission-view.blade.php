<table style='width:100%'>
        @foreach ($routes as $rootKey => $route) 
                <div class="sub-function-container">
                    <p> <strong> <h4> {{ucfirst($rootKey)}} </h4> </strong> </p>
                    @foreach($route as $key => $name)
                        <div class="form-check sub-function-single">
                            <input class="form-check-input" type="checkbox" name="{{$name}}" id="{{$name}}" {{(isset($permissions[$name]) && $permissions[$name] == 1) ? 'Checked': ''}}>
                            <label for="{{$name}}">
                                @php $function_name = explode('.',$name) @endphp
                                {{ ucwords(str_replace('-', ' ', end($function_name))) }}
                            </label>
                        </div>
                    @endforeach
                </div>
            <hr/>
        @endforeach
        
</table>
<hr>
<div class="form-group">
    <div class="col-lg-offset-5 col-lg-10">
        <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
        <a  href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
    </div>
</div>