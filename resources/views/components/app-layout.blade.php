<!doctype html>
<html lang="en" class="h-full {{ config('cockpit.dark') ? 'bg-gray-800' : 'bg-gray-100' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cockpit</title>

    <link href="{{ mix('css/app.css', 'vendor/cockpit') }}" rel="stylesheet">
</head>
<body class="h-full {{ config('cockpit.dark') ? 'dark' : '' }}" x-data="{}">
    <div class="min-h-full">
        <x-cockpit::nav/>

        <div class="max-w-7xl mx-auto py-10">
            {{ $slot }}
        </div>
    </div>

    <script src="{{ mix('js/app.js', 'vendor/cockpit') }}"></script>
</body>
</html>
