<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('img/icon-bisi.png') }}">
    <title>
        Barrier Gate
    </title>
    <!--  Fonts and icons  -->
    <link href="{{ asset('assets/css/font-google.css?v=1.0.0') }}" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('assets/css/all.min.css?v=1.0.0') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fontawesome.min.css?v=1.0.0') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    {{-- Datatable --}}
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-datatable/css/dataTables.bootstrap4.min.css?v=1.0.1') }}"
        type="text/css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-datatable/css/buttons.bootstrap4.min.css?v=1.0.1') }}" type="text/css">
    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css?v=1.1.4') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/css/sweetalert2.min.css?v=1.0.0') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/jquery-confirm/jquery.confirm.min.css?v=1.0.0') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/styles_full.css?v=1.1.6') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css?v=1.0.0') }}" />

    {{-- Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datepicker.min.css?v=1.0.0') }}">
    <link rel="stylesheet" href="{{ asset('assets/date-range/daterangepicker.css?v=1.0.1') }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="{{ $class ?? '' }}">

    @guest
        @if (in_array(request()->route()->getName(), ['login']))
            @yield('content')
        @else
            <div class="min-height-100 bg-primary position-absolute w-100"></div>
            <main class="main-content border-radius-lg">
                @yield('content')
                <!-- Image loader -->
                <div class="ajax-loader">
                    <img src="{{ asset('img/loader_2.gif') }}" class="img-responsive" />
                </div>
                <!-- Image loader -->
            </main>
            @include('layouts.footers.auth.footer')
        @endif

    @endguest


    @auth
        <div class="min-height-300 bg-primary position-absolute w-100"></div>
        <main class="main-content border-radius-lg">
            @yield('content')

        </main>
        @include('layouts.footers.auth.footer')

    @endauth


    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.safeform.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/jquery-confirm/jquery-confirm.min.js') }}"></script>

    {{-- Datepicker --}}
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/knockout-min.js') }}"></script>
    <script src="{{ asset('assets/date-range/daterangepicker.js') }}"></script>
    {{-- E-charts --}}
    <script src="{{ asset('assets/echarts/dist/echarts-en.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/scadavis/synopticapi.js') }}"></script>

    <script src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>

    <input type="hidden" name="ws_url" value="{{ env('WS_URL') }}">
    <input type="hidden" name="apk_name" value="{{ env('APP_NAME') }}">

    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <script>
        function refreshAt(hours, minutes, seconds) {
            var now = new Date();
            var then = new Date();

            if (now.getHours() > hours ||
                (now.getHours() == hours && now.getMinutes() > minutes) ||
                now.getHours() == hours && now.getMinutes() == minutes && now.getSeconds() >= seconds) {
                then.setDate(now.getDate() + 1);
            }
            then.setHours(hours);
            then.setMinutes(minutes);
            then.setSeconds(seconds);

            var timeout = (then.getTime() - now.getTime());
            setTimeout(function() {
                window.location.reload(true);
            }, timeout);
        }

        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            refreshAt(0, 1, 0);

            $(`.select-2`).select2({
                placeholder: 'Search...',
                width: "100%",
                allowClear: true,
            });

            $("input[name=date_start]").daterangepicker({
                forceUpdate: false,
                single: true,
                timeZone: 'Asia/Jakarta',
                startDate: moment().subtract(1, 'days'),
                periods: ['day', 'week', 'month', 'year'],
                // standalone: true,
                callback: function(start, period) {

                    var title = start.format('YYYY-MM-DD');
                    $(this).val(title)
                }
            });
            $("input[name=date_end]").daterangepicker({
                forceUpdate: false,
                single: true,
                timeZone: 'Asia/Jakarta',
                startDate: moment().subtract(0, 'days'),
                periods: ['day', 'week', 'month', 'year'],
                // standalone: true,
                callback: function(start, period) {

                    var title = start.format('YYYY-MM-DD');
                    $(this).val(title)
                }
            });
            $("input[name=arrival_date]").daterangepicker({
                forceUpdate: false,
                single: true,
                timeZone: 'Asia/Jakarta',
                startDate: moment().subtract(0, 'days'),
                periods: ['day'],
                callback: function(start, period) {
                    var title = start.format('YYYY-MM-DD');
                    $(this).val(title)
                }
            });
        });
    </script>

    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->

    @stack('js')
    @yield('script')
</body>

</html>
