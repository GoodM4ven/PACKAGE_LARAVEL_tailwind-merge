<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >
    <meta
        http-equiv="X-UA-Compatible"
        content="ie=edge"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title>Demo - Testing</title>

    <!-- Styles -->
    <link
        href="{{ asset('build/demo.css') }}"
        rel="stylesheet"
    >
    @livewireStyles
</head>

<body class="antialiased">
    @livewire('tailwind-merge::merger')

    <!-- Body Scripts -->
    <script src="{{ asset('build/demo.js') }}"></script>
    @livewireScriptConfig

    <!-- Injections -->
    @stack('injections')
</body>

</html>
