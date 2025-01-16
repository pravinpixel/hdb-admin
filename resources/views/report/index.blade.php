@extends('layouts.default') @section('content') @include('flash::message') @include('flash')

<div class="row">
    <div class="col-sm-12 col-md-6">
        <h2 class="text-dark"> Bulk Upload </h2>
    </div>
    <div class="col-sm-12 col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(Sentinel::inRole('admin'))
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                @elseif(Sentinel::inRole('manager'))
                <li class="breadcrumb-item"><a href="{{route('manager.dashboard')}}">Dashboard</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('bulk-upload.index')}}">Bulk Upload </a> </li>
            </ol>
        </nav>
    </div>
</div>
<div class="card">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form">
                <form action="{{ route('staff-bulk-upload.update') }}" method="post" class="cmxform form-horizontal tasi-form">
                    @csrf
                    <div class="form-group">
                        {!! Form::label('bulkupload', 'Staff Bulk Upload', ['class' => 'control-label col-lg-2'], false) !!}
                        <div class="col-lg-10">
                            <input type="file" name="staffs" class="form-control" required> 
                            @error('staffs')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                      <div class="form-group">
                            {!! Form::label('bulkupload', 'Download Sample Excel', ['class' => 'control-label col-lg-2'], false) !!}
                           <div class="col-lg-10">
                      <a href="{{ asset('assets/pages/excel/staff.xlsx') }}"><button type="button"
                                 class="btn btn-primary">Download</button></a>
                                 </div>

                     </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10" style="margin-top: 20px !important; ">
                            <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
                            <a href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <hr>
      <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="form">
                <form action="{{ route('book-bulk-upload.update') }}" method="post" class="cmxform form-horizontal tasi-form">
                    @csrf
                    <div class="form-group">
                        {!! Form::label('bulkupload', 'Book Bulk Upload', ['class' => 'control-label col-lg-2'], false) !!}
                        <div class="col-lg-10">
                            <input type="file" name="books" class="form-control" required> 
                            @error('books')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                     <div class="form-group">
                            {!! Form::label('bulkupload', 'Download Sample Excel', ['class' => 'control-label col-lg-2'], false) !!}
                           <div class="col-lg-10">
                        <a href="{{ asset('assets/pages/excel/book.xlsx') }}"><button type="button"
                                 class="btn btn-primary">Download</button></a>
                                 </div>

                     </div>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10" style="margin-top: 20px !important; ">
                            <button class="btn btn-success waves-effect waves-light" type="submit">Save</button>
                            <a href="{{url()->previous()}}" class="btn btn-default waves-effect" type="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection @push('page_script') @endpush