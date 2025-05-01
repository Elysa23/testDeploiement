<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">

   <!-- 29/04 : Ajout message d'erreur sur accès ajouter-utilisateur par non-admin -->
   @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('success') && !session('redirect'))
    <div id="success-popup" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded shadow-lg text-center max-w-md w-full">
        {{ session('success') }}
        </div>
    </div>
    <script>
        setTimeout(function() {
            var popup = document.getElementById('success-popup');
            if (popup) {
                popup.style.display = 'none';
            }
        }, 2500); // 2500 ms = 2,5 secondes
    </script>
@endif

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
            @yield('content')
            </main>
        </div>

         <!--Message de succès ou d'erreur suite suppression utilisateur -->

         @if(session('success') && !session('redirect'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif
    </body>
</html>
