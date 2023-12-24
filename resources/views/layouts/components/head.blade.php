<head><base href=""/>
    <title>@yield('title')</title>
    <meta charset="utf-8" />
    <meta name="description" content="Đăng kí ngay Nextgo - Giải pháp quản lí bán hàng hàng đầu Việt Nam" />
    <meta name="keywords" content="nextgo, fullsnackteam, bán hàng, sale" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_VN" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Nextgo - Giải pháp quản lí bán hàng hàng đầu Việt Nam" />
{{--    <meta property="og:url" content="https://keenthemes.com/metronic" />--}}
    <meta property="og:site_name" content="Nextgo | Giải pháp quản lí bán hàng" />
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo-small.svg')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
    <script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
@stack('styles')
</head>
