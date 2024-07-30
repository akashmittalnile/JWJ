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

    <link rel="stylesheet" type="text/css" href="{{ assets('assets/css/responsive.css') }}">

    <!-- -------- end css ----------- -->

    <!-- -------- script ----------- -->
    <script src="{{ assets('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/apexcharts/apexcharts.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/OwlCarousel/owl.carousel.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/js/dashboard-function.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/js/function.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.12.0/moment.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" rel="stylesheet">
    <!-- -------- end script ----------- -->
</head>

<body class="main-site ccj-panel">

    <!-- -------- preloader ----------- -->
    <div id="preloader">
        <svg class="pl" width="240" height="240" viewBox="0 0 240 240">
            <circle class="pl__ring pl__ring--a" cx="120" cy="120" r="105" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 660" stroke-dashoffset="-330" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--b" cx="120" cy="120" r="35" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 220" stroke-dashoffset="-110" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--c" cx="85" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
            <circle class="pl__ring pl__ring--d" cx="155" cy="120" r="70" fill="none" stroke="#000" stroke-width="20" stroke-dasharray="0 440" stroke-linecap="round"></circle>
        </svg>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
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