<!doctype html>
<html lang="en" class="h-full"
      x-data="darkMode(@js(config('cockpit.dark')))"
      x-init="init()"
      x-bind:class="{ 'dark bg-gray-800': darkMode, 'bg-gray-100': !darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cockpit</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,400;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cutive+Mono&display=swap" rel="stylesheet">
    <link href="{{ mix('css/app.css', 'vendor/cockpit') }}" rel="stylesheet">
</head>
<body class="h-full {{ config('cockpit.dark') ? 'dark' : '' }}" x-data="{}">
    <div class="min-h-full">
        <x-cockpit::nav/>

        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </div>

    <script src="{{ mix('js/app.js', 'vendor/cockpit') }}"></script>
</body>
</html>
