 <div class="row">  
    <div class="col-lg-6">
       
        <div class="form-group">
            <label for="title">Title: <span style="color: red">*</span></label>
            {!! Form::text('title', null , ['class' => 'form-control']) !!}
             @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        <div class="form-group">
            <label for="author">Author: <span style="color: red">*</span></label>
            {!! Form::text('author', null, ['class' => 'form-control']) !!}
             @error('author')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        <div class="form-group">
            <label for="barcode">Acession/Barcode Number:</label>
             {!! Form::text('barcode', null, ['class' => 'form-control']) !!}
              @error('barcode')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        <div class="form-group">
            <label for="rfid">RFID:<span style="color: red">*</span></label>
             {!! Form::text('item_ref', null , ['class' => 'form-control']) !!}
              @error('item_ref')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        <div class="form-group">
            <label for="rfid">Location:<span style="color: red">*</span></label>
             {!! Form::text('location', null , ['class' => 'form-control']) !!}
              @error('location')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>

        
       
           <div class="form-group">
            <label class="mr-4" for="status">Status : </label>

            {!! Form::radio('status', 'active', false, ['class' => '', 'id' => 'active'])  !!}
            <label for="status">Active</label>

            {!! Form::radio('status', 'inactive', true, ['class' => '', 'id' => 'inactive'])  !!}
            <label for="status">Inactive</label>

        </div>

    </div>


    <div class="col-lg-6">
     <div class="form-group">
            <label for="isbn">ISBN: <span style="color: red">*</span></label>
            {!! Form::text('isbn', null, ['class' => 'form-control']) !!}
             @error('isbn')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
         <div class="form-group">
            <label for="call_number">Call Number:</label>
             {!! Form::text('call_number', null, ['class' => 'form-control']) !!}
              @error('call_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
         <div class="form-group">
            <label for="subject">Subject:<span style="color: red">*</span></label>
           {!! Form::text('subject', null, ['class' => 'form-control']) !!}
            @error('subject')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
    
        </div>
           <div class="form-group">
            <label for="Langugae">Langugae:<span style="color: red">*</span></label>
              {!! Form::select('language_id', ['' => '--select Langugae--'], null, ['class' => 'form-control', 'id' => 'language_id']) !!}
               @error('language_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
      
        <div class="form-group">
            <label for="rfid">Collection:<span style="color: red">*</span></label>
             {!! Form::text('collection', null , ['class' => 'form-control']) !!}
              @error('collection')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        <div class="form-group">
            <label for="rfid">Imprint:<span style="color: red">*</span></label>
             {!! Form::text('imprint', null , ['class' => 'form-control']) !!}
              @error('imprint')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
         @enderror
        </div>
        
    </div>
</div>