<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TEDGEN Network</title>
    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body>
    <header>
        @auth
            @if (request()->routeIs('customer.index'))
                <a href="{{ route('customer.create') }}" class="btn-success float-end">Create New Customer</a>
            @endif


        @endauth
        {{ Breadcrumbs::render() }}
    </header>

    <div class="main-content">

        <div class="sidebar">

            @include('layouts.newsidebar')

        </div>

        <div class="content">
            @if (session('success'))
                <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
                    {{ session('success') }}
                </div>
            @endif

            {{ $slot }}

        </div>

    </div>

    <footer>
        // Footer stuff
    </footer>
</body>











</html>
