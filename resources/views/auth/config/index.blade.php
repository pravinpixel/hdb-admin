@extends('layouts.default')

@section('content')

@include('flash::message')
@include('flash')

    <div class="row">   
        <div class="col-sm-12 col-md-6">         
            <h2 class="text-dark"> Config </h2>
        </div>
        <div class="col-sm-12 col-md-6">         
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(Sentinel::inRole('admin'))
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                    @elseif(Sentinel::inRole('manager')) 
                    <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{route('config.index')}}">Config </a> </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <label> Last trigger updated at:  {{ $config->last_cron_updated ?? ''}} </label>
                <a href="#" class="btn btn-info pull-right" onclick="event.preventDefault(); document.getElementById('cron-form').submit();"> Trigger overdue mail </a>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                {!! Form::open(['route' => 'config.store',"id" => "configFrom", "Method" => "POST"]) !!}
                    <label for="enable-email"> Enable Email:</label>
                    @if($config->enable_email == 1)
                        <input type="checkbox" class="" name="enable_email" id="enable_email" checked>
                    @else
                        <input type="checkbox" class="" name="enable_email" id="enable_email" >
                    @endif
                    <button class="btn btn-success pull-right"> Save </button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    {{-- <div class="card">
        <div class="row">
            <div class=="col-lg-6">
               
                    <div class="col-sm-12 col-md-12">  
                        <div class="row">
                            <div class="col-md-2">
                               
                            </div>
                        </div>
                        <div class="pull-left">
                            <br>
                           
                        </div>
                    </div>
                
            </div>
            <div class="col-lg-6">
                <div class="form-group">  
               
                    
                </div>
            </div>
        </div>
    </div> --}}

    <form id="cron-form" action="{{ route('config.call-cron') }}" method="POST" class="d-none">
        @csrf
    </form>
   
@endsection
@push('page_script')
@endpush

