<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - TedGeneration</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('theme/css/styles.css') }}" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!--Datatables Scripts/styles-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<body class="sb-nav-fixed">
    @include('theme.header')
    <div id="layoutSidenav">
        @include('theme.sidebar')
        <div id="layoutSidenav_content">
            <main>
                @if (session('success'))
                    <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
                        {{ session('success') }}
                    </div>
                @endif
                @yield('content')
            </main>
            @include('theme.footer')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="{{ asset('theme/js/scripts.js') }}"></script>

</body>

</html>
