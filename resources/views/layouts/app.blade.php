<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Nusa Plants House' }}</title>

    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}

    {{-- <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {

                            50: '#F4F9F5',

                            100: '#E2EFE3',

                            600: '#40916C',

                            800: '#1B4332',

                        }

                    }

                }

            }

        }
    </script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>


    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <x-partials.head-assets />
</head>
@stack('scripts')

<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <livewire:navbar />

    <main class="flex-grow">
        {{ $slot }}
        {{-- @livewire('home') --}}
    </main>

    <x-footer />

</body>

</html>
