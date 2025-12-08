<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title inertia>{{ config('app.name', 'KeyPerson') }}</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/key-color.png') }}" />

    {{-- Scripts --}}
    @routes
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
</head>

<body class="bg-gray-50 font-sans antialiased selection:bg-violet-100 selection:text-violet-700">
    @inertia
</body>

</html>