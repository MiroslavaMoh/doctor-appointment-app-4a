@props([
    'title' => config('app.name', 'Laravel'),
    'breadcrumbs' => []
    ])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--<title>{{ config('app.name', 'Laravel') }}</title>-->
        <title>{{ $title }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Font awesome -->
        <script src="https://kit.fontawesome.com/b66dd5d28c.js" crossorigin="anonymous"></script>

        <!--SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <!-- Botones más decentes Wire UI -->
        <wireui:scripts />

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-50">
    <!-- Inicia segmento Sidebar with navbar de Flowbite -->  
    <!-- Fuente: https://flowbite.com/docs/components/sidebar/#sidebar-with-navbar -->   
        @include('layouts.includes.admin.navigation')

        @include('layouts.includes.admin.sidebar')

        <div class="p-4 sm:ml-64">
            <div class="h-5"></div>
        <!-- Margin top 14px -->
        <div class="mt-14 flex items-center justify-between w-full">
             @include('layouts.includes.admin.breadcrumb', ['breadcrumbs' => $breadcrumbs])   
        </div>

        {{-- Contenido principal --}}
    <main>
        <div class="max-w-7xl mx-auto">

            {{-- Título y botón de acción --}}
            <div class="flex justify-between items-center mb-6">
                @if (isset($title))
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">
                        {{ $title }}
                    </h1>
                @endif

                @if (isset($action))
                    <div>
                        {{ $action }}
                    </div>
                @endif
            </div>

        </div>
        {{$slot}}
    </main>

        <!-- Termina segmento Sidebar with navbar de Flowbite -->   
        @stack('modals')

        @livewireScripts
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>

        <!-- SweetAlert2 -->
       <!--@if(@session('swal'))
            <script>
                Swal.fire(@json('swal'));
            </script>-->
            @if (session('swal'))
                <script>
                    Swal.fire({
                        icon: '{{ session('swal.icon') }}',
                        title: '{{ session('swal.title') }}',
                        text: '{{ session('swal.text') }}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif
        @endif

            <!-- Confirmación de eliminación -->
            <script>
                document.querySelectorAll('.delete-role-form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault(); // evita el envío inmediato

                        Swal.fire({
                            title: "¿Estás seguro?",
                            text: "Este cambio no se puede revertir.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "¡Sí, eliminarlo!",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // ahora sí envía el form
                            }
                        });
                    });
                });
            </script>

        
    </body>
</html>
