<!doctype html>
<html>
<head>
    @include('includes.head')
    @stack('page_css') 
</head>
<body>
<div class="load-bg">
<div class="loader"></div></div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <input type="hidden" name="baseurl" value="{{URL::to('/')}}" id="baseurl">
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('includes.sidebar')
        <!-- /#sidebar-wrapper -->
        <!-- Page Content -->
        <div id="page-content-wrapper">
            @include('includes.header')
            <div class="container-fluid">
                <div class="content-page">
                    <div class="content">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>  
        <!-- /#page-content-wrapper -->
    </div>
    @include('includes.delete-modal')
    @include('includes.success-modal')
    @include('includes.error-modal')
        <!-- /#wrapper -->
</body>
</html>
@include('includes.footer')
@stack('page_script')

