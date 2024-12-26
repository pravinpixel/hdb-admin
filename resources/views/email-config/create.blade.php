
@extends('layouts.default')
@section('content')
    @include('flash::message')
    @include('flash')

    <div class="row">
        <div class="col-sm-12 col-md-6">         
            <h2 class="text-dark">@lang('email.email-config')</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12"> 
          <div class="card">
                <div class="row">
                    {!! Form::model($emailConfig, ['route' => ['email-config.update', $emailConfig->id],"id" => "emailConfigForm", "Method" => "POST", "class" => "cmxform form-horizontal tasi-form"]) !!}
                        @include('email-config.field')
                   {!! Form::close() !!}
                </div>
            </div>   
        </div>    
    </div>

@endsection

@push('page_css')
	<link rel="stylesheet" href="{{ asset('dark/assets/plugins/jquery-datatables-editable/datatables.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/pagec/employee.css') }}">
    <link href="{{ asset('dark/assets/plugins/select2/dist/css/select2.css')}}" rel="stylesheet" type="text/css">
@endpush

@push('page_script')
<script type="text/javascript" src="{{ asset('assets/datatables/js/jquery.dataTables.js') }}" ></script>
<script type="text/javascript" src="{{ asset('assets/datatables/js/dataTables.bootstrap.js') }}" ></script>
<script type="text/javascript" src="{{ asset('dark/assets/plugins/select2/dist/js/select2.min.js')}}"> </script>
<script>
    $(function(){
        $("#email-config-save").prop('disabled', true);
    });
    
    function generatePassword(e)
    {
        e.preventDefault();
        if($("#password").val() == '') {
            errorAlert('Password field is required');
            $("#email-config-save").prop('disabled', false);
            return false;
        }
        $.ajax({
            url: '{{ route("email-config.get-generate-password") }}',
            method: 'post',
            data: {data: $("#password").val()},
            success: function(res){
                if(res.status == true) {
                    $("#email-config-save").prop('disabled', false);
                    $("#generate-password").val(res.data);
                    return false;
                }
                errorAlert(res.msg);
                return false;
            }, error: function(){
                console.log('error email config')
            }
            
        })
    }
</script>
@endpush