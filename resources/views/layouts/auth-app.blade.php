<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ assets('assets/images/logo.svg') }}">
    <title>@yield('title',config('constant.siteTitle'))</title>

    <!-- -------- css ----------- -->
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ assets('assets/plugins/line-awesome/css/line-awesome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('css')
    <!-- -------- end css ----------- -->

    <!-- -------- script ----------- -->
    <script src="{{ assets('assets/js/jquery-3.7.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
    <!-- -------- end script ----------- -->
</head>

<body>

    <!-- -------- Content ----------- -->
    @yield('content')
    <!-- -------- end Content----------- -->

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
    </script>
    @stack('js')
    <!-- -------- end script ----------- -->

</body>

</html>