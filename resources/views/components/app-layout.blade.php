<!doctype html>
<html lang="en" class="h-full"
      x-data="toggleTheme(@js(config('cockpit.dark')))"
      x-init="init()"
      x-cloak
      x-bind:class="{ 'dark bg-dark-secondary': darkMode, 'bg-gray-100': !darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Cockpit</title>
    <link rel="icon" href="{{ url('vendor/cockpit/assets/favicon.svg') }}" sizes="any" type="image/svg+xml">

    <link href="{{ mix('css/app.css', 'vendor/cockpit') }}" rel="stylesheet">

    <template x-if="darkMode">
        <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    </template>
</head>
<body class="h-full" x-data="{ errorDetailLayoutMinimal : false }">
    <div class="min-h-full">
        <x-cockpit::nav/>

        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </div>
    </div>

    <div class="w-full text-center text-dark-primary p-12 bg-white dark:bg-dark-primary dark:text-primary sm:space-x-2 md:space-x-14">
        <a href="#" class="hover:underline">About</a>
        <a href="#" class="hover:underline">Terms and Conditions</a>
        <a href="#" class="hover:underline">DevSquad</a>
        <a href="#" class="hover:underline">Documentation</a>
        <a href="#" class="hover:underline">GitHub</a>
    </div>

    <x-cockpit::toast />

    <script src="{{ mix('js/app.js', 'vendor/cockpit') }}"></script>

    @stack('scripts')
</body>
</html>
