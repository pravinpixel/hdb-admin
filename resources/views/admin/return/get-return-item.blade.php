<style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover {
  background-color: #ccc; 
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
</style>
<br>
<br>
<div class="col-sm-12 col-md-12"> 
    <h4 for="taken-all-items"> Taken Items </h4>
    @if($checkouts->count() > 0)
      @foreach($checkouts as $key => $checkout)
          <button class="accordion">{{ $checkout->item->item_name }} ({{ $checkout->item->item_id }})</button>
          <div class="panel">
              <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                          <img src="{{asset('uploads/'.$checkout->item->cover_image)}}" border="0" width="100" class="img-rounded" align="center"> 
                      </div>
                  </div>

                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="type">Item ID: </label>
                          <span for="type"> {{ $checkout->item->item_id }} </span>
                      </div>
                      <div class="form-group">
                          <label for="type">Item Name: </label>
                          <span for="type"> {{ $checkout->item->item_name }} </span>
                      </div>
                      
                  </div>

                  <div class="col-md-6">
                     <div class="form-group">
                          <label for="type">Date of return:</label>
                          <span for="type">{{ $checkout->date_of_return }} </span>
                      </div>
                      {{-- <div class="form-group">
                          <label for="type"></label>
                          <span for="type"> </span>
                      </div> --}}
                  </div>
              </div>
          </div>
      @endforeach
    @else 
      <div class="container">
          <h6>No item taken</h6>
      </div>
    @endif
</div>
    
<script>
var acc = document.getElementsByClassName("accordion");
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}
</script>