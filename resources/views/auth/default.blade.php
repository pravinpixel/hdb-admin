<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <!-- Meta, title, CSS, favicons, etc. -->
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
	<meta name="author" content="Coderthemes">
	
	

	<title>{{ env('APP_NAME') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('dark/assets/images/favicon.ico') }}">
	<link href="{{ asset('dark/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/core.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/icons.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/components.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/pages.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/menu.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/responsive.css')}}" rel="stylesheet" type="text/css">
	<link href="{{ asset('dark/assets/css/common.css')}}" rel="stylesheet" type="text/css">
    <style type="text/css">
        .login-box, .register-box {
            width: 420px;
             margin: 7% auto;
        }
    </style>
@stack('css')


    <!-- Embed browser icon -->
  

</head>

<body class="hold-transition login-page">

<!-- Start: Main -->
@yield('content')
<!-- End: Main -->
 <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{ asset('dark/assets/js/jquery.min.js')}}"></script>
        <script src="{{ asset('dark/assets/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript">
         $(".toggle-password").click(function() {
              $(this).toggleClass("glyphicon-eye-open glyphicon-eye-close");
                  var input = $($(this).attr("toggle"));
                  if (input.attr("type") == "password") {
                    input.attr("type", "text");
                  } else {
                    input.attr("type", "password");
                  }
            });
        
            document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alert after 5 seconds
    setTimeout(function() {
        $('.alert-dismissible').alert('close');
    }, 3000);
});

        </script>
    </body>

<!-- Mirrored from moltran.coderthemes.
</html>