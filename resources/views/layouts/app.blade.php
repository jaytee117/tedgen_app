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
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 md:pl-64">
        @if (session('success'))
            <div id="flash" class="p-4 text-center bg-green-50 text-green-500 font-bold">
                {{ session('success') }}
            </div>
        @endif
        <header>
            <nav class="relative flex items-center w-full px-4">
                <!-- Mobile Header -->
                <div class="inline-flex items-center justify-center w-full md:hidden">
                    <a href="#" @click="open = true" @click.away="open = false" class="absolute left-0 pl-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 stroke-blue-600" fill="currentColor"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                        </svg>
                    </a>
                </div>
                @auth
                    <span class="border-r-2 pr-2">Hi there, {{ Auth::user()->name }}</span>
                    @if (request()->routeIs('customer.index'))
                        <a href="{{ route('customer.create') }}">Create New Customer</a>
                    @endif
                    @include('layouts.sidebar')
                @endauth

            </nav>
        </header>

        <main class=" min-h-[200px] pt-4 w-full px-4 mx-auto">
            {{ $slot }}
        </main>
    </div>
</body>

</html>
