@extends('auth.default') 
@section('content')
    <div class="wrapper-page">
        @include('flash::message')
        <div class="panel panel-pages">
            <div class="panel-heading"> 
                <div class="bg-overlay"></div>
               
            </div> 
            <div class="panel-body">
            <img src="{{ asset('dark/assets/images/logo.png')}}" style="width: 248px;height: 51px;padding-left: 73px;">
            <h1 style="font-size: 39px;margin-top:20px;"><span style="color:black;">ADMIN</span>  <span style="color:#ff0000;">LOGIN</span></h1>
            <form class="form-horizontal m-t-20" method="POST" action="{{ route('login') }}/">
                    @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <input class="form-control input-lg  @error('email') is-invalid @enderror" type="text" required="" name="email" value="{{ old('email') }}" placeholder="Email">
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
        
                <div class="form-group">
                    <div class="col-xs-12">
                      <input id="password-field" type="password" placeholder="Password" class="form-control input-lg @error('password') is-invalid @enderror" name="password" value="">

                      <span toggle="#password-field" class="glyphicon glyphicon-eye-open field-icon toggle-password"></span>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group text-center m-t-40">
                    <div class="col-xs-6">
                        <button class="btn btn-primary btn-lg w-lg loginbtn" type="submit">Log In</button>
                    </div>
                    <div class="col-xs-6">
                        <a href="{{ route('forgot-password') }}"> Forgot password? </a>
                    </div>
                </div>
            </form> 
            </div>                                 
            
        </div>
    </div>
@endsection

