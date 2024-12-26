@extends('auth.default') 
@section('content')
    <div class="wrapper-page">
        @include('flash::message')
        <div class="panel panel-pages">
            <div class="panel-body">
            <h4>Reset your password</h4>
            <form class="form-horizontal m-t-20" method="GET" action="{{ route('send-forget-email') }}/">
                    @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control input-lg  @error('email') is-invalid @enderror" type="text" required name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
                <div class="form-group text-center m-t-20 pull-right">
                    <div class="col-xs-6">
                        <button class="btn btn-primary btn-sm w-sm waves-effect waves-light" type="submit">Send email</button>
                    </div>
                </div>
            </form> 
            </div>                                 
            
        </div>
    </div>
@endsection

