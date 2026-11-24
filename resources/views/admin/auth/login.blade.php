<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin – {{ $site->get('site_name') }}</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-gradient-to-br from-husk-900 to-husk-700 px-4 font-sans antialiased">
    <main class="w-full max-w-sm rounded-3xl bg-white p-8 shadow-2xl">
        <div class="text-center">
            <img src="{{ asset('images/logo.svg') }}" alt="" class="mx-auto size-16 rounded-full">
            <h1 class="mt-4 font-display text-2xl font-bold text-husk-900">Admin {{ $site->get('site_name') }}</h1>
            <p class="mt-1 text-sm text-stone-500">Masuk untuk mengelola website</p>
        </div>
        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5">
            @csrf
            <x-admin.input name="email" label="Email" type="email" required autofocus autocomplete="username" />
            <x-admin.input name="password" label="Password" type="password" required autocomplete="current-password" />
            <x-admin.checkbox name="remember" label="Ingat saya" />
            <button class="w-full rounded-xl bg-husk-700 py-3 font-semibold text-white hover:bg-husk-800">Masuk</button>
        </form>
    </main>
</body>
</html>
