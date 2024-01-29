<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ assets('assets/images/logo.svg') }}">
    <title>@yield('title',config('constant.siteTitle'))</title>

    <!-- -------- css ----------- -->
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/header-footer.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/apexcharts/apexcharts.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/OwlCarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('css')
    <!-- -------- end css ----------- -->

    <!-- -------- script ----------- -->
    <script src="{{ assets('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/apexcharts/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/js/dashboard-function.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/js/function.js') }}" type="text/javascript"></script>
    <!-- -------- end script ----------- -->
</head>

<body class="main-site ccj-panel">

    <!-- -------- preloader ----------- -->
    <div id="preloader">
        <div class="loader-bg">
            <div class="loader-p">
            
            </div>
        </div>
    </div>
    <!-- -------- end preloader ----------- -->

    <!-- -------- Content ----------- -->
    <div class="page-body-wrapper">
        @include('layouts.sidebar')
        <div class="body-wrapper">
            @include('layouts.header')
            @yield('content')
        </div>
    </div>
    <!-- -------- end Content----------- -->

    <!-- -------- script ----------- -->
    <script src="{{ assets('assets/plugins/jquery-validation/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @if(Session::has('success'))
    <script>
        toastr.success("{{ Session::get('success') }}");
    </script>
    @elseif(Session::has('error'))
    <script>
        toastr.error("{{ Session::get('error') }}");
    </script>
    @endif
    <script type="text/javascript">
        window.addEventListener('load', function() {
            var preloader = document.getElementById('preloader');
            $("#preloader").delay(500).fadeOut("slow");
        });
        let baseUrl = "{{ url('/') }}";
    </script>
    @stack('js')
    <!-- -------- end script ----------- -->

</body>
</html>