<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TED Generation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    @vite('resources/css/app.css')
</head>

<body>
    <header>
        <nav>

        </nav>
    </header>
    <main class="container">
        <form action="{{ route('login') }}" method="POST" style="margin-top:100px">
            @csrf
            <h2>Log In to Your Account</h2>
            <label for="email">Email</label>
            <input type="email" name="email" required value="{{ old('email') }}" class="form-control">
            <label for="password">Password</label>
            <input type="password" name="password" required class="form-control">
            <button type="submit" class="btn btn-success mt-4">Log In</button>
            <!-- validation errors -->
            @if ($errors->any())
                <ul class="px-4 py-2 bg-red-100">
                    @foreach ($errors->all() as $error)
                        <li class="my-2 text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </form>
    </main>
</body>

</html>



