 <div class="row">  
      <div class="col-sm-12 col-md-6 col-lg-3"> 
            <div class="form-group @if($errors->has('language')) has-error @endif">
               <label for="usr" class="control-label">Language: <span style="color: red">*</span></label> <br><br>
                {!! Form::text('language',null , ['class' => 'form-control danger', 'id' => 'language']) !!}
               @if($errors->has('language'))
                  <label for="language" class="error">{{ $errors->first('language') }}</label>
               @endif
            </div>
   </div>    
</div>