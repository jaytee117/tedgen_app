<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TED Generation</title>
    @vite('resources/css/app.css')
</head>

<body>
    <header>
        <nav>
            <h1>
                TED Generation
            </h1>
        </nav>
    </header>
    <main class="container">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <h2>Log In to Your Account</h2>
            <label for="email">Email</label>
            <input type="email" name="email" required value="{{ old('email') }}">
            <label for="password">Password</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn mt-4">Log In</button>
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
