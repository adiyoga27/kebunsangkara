@props(['title'])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title }} – Admin {{ $site->get('site_name') }}</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-100 font-sans text-stone-800 antialiased">
    <x-admin.sidebar :site-name="$site->get('site_name')" />

    <main class="min-w-0 lg:ml-72">
        <div class="mx-auto max-w-6xl px-4 py-8 sm:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-husk-200 bg-husk-50 px-4 py-3 text-sm font-medium text-husk-800" role="status">{{ session('status') }}</div>
            @endif
            @if ($errors->has('user'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800" role="alert">{{ $errors->first('user') }}</div>
            @endif

            {{ $slot }}
        </div>
    </main>
</body>
</html>
