 <div class="row">  
    <div class="col-lg-6">
       
        <div class="form-group">
            <label for="title">Title: <span style="color: red">*</span></label>
            {!! Form::text('title', null , ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label for="author">Author: <span style="color: red">*</span></label>
            {!! Form::text('author', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label for="barcode">Acession/Barcode Number:</label>
             {!! Form::text('barcode', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group">
            <label for="rfid">RFID:<span style="color: red">*</span></label>
             {!! Form::text('item_ref', $item_ref , ['class' => 'form-control', 'readonly' => 'readonly']) !!}
        </div>
        
          <div class="form-group">
            <label for="Langugae">Langugae:<span style="color: red">*</span></label>
              {!! Form::select('language_id', ['' => '--select Langugae--'], null, ['class' => 'form-control', 'id' => 'language_id']) !!}
        </div>
         <div class="form-group">
            <label for="status">Status</label>
        {!! Form::checkbox('status', null, false, ['class' => '', 'id' => 'status'])  !!}
        </div>

    </div>


    <div class="col-lg-6">
     <div class="form-group">
            <label for="isbn">ISBN: <span style="color: red">*</span></label>
            {!! Form::text('isbn', null, ['class' => 'form-control']) !!}
        </div>
         <div class="form-group">
            <label for="call_number">Call Number:</label>
             {!! Form::text('call_number', null, ['class' => 'form-control']) !!}
        </div>
         <div class="form-group">
            <label for="subject">Subject:<span style="color: red">*</span></label>
           {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    
        </div>
      
  <div class="form-group">
            <label for="location">Location:<span style="color: red">*</span></label>
           {!! Form::text('location', null, ['class' => 'form-control']) !!}
        </div>
        
    </div>
</div>