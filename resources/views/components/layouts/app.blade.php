<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'E-commerce' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        <!-- Livewire Alert CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@5/dark.css">

    </head>
    <body class="bg-slate-200 dark:bg-slate-700">
        @livewire('partials.navbar')
        <main>
            {{ $slot }}
        </main>
        @livewire('partials.footer')
    @livewireScripts
        <!-- Voeg dit toe voor Livewire Alert JS -->
        <script src="https://cdn.jsdelivr.net/npm/livewire-alert@1.0.0/dist/livewire-alert.js"></script>

        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('alert', (params) => {
                    Swal.fire({
                        icon: params.type,
                        title: params.title,
                        position: params.position || 'center',
                        timer: params.timer || null,
                        toast: params.toast || false,
                        showConfirmButton: !params.toast,
                    });
                });
            });
        </script>

    </body>
</html>
