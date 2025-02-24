<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Red Internacional de Cátedras Martianas | Administracion</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="{{ asset('img/assets/icono.jpg') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const cards = document.querySelectorAll(".activity-card");
            let currentIndex = 0;
        
            function showCard(index, direction) {
                cards.forEach((card, i) => {
                    card.classList.remove("animate__backInLeft", "animate__backInRight", "hidden", "active");
        
                    if (i === index) {
                        card.classList.add("active");
                        card.classList.remove("left", "right");
        
                        // Aplica la animación según la dirección
                        if (direction === "next") {
                            card.classList.add("animate__animated", "animate__backInRight");
                        } else {
                            card.classList.add("animate__animated", "animate__backInLeft");
                        }
                    } else if (i < index) {
                        card.classList.add("left");
                    } else {
                        card.classList.add("right");
                    }
                });
            }
        
            // Botón "Next" (Siguiente)
            document.querySelectorAll(".next-btn").forEach((btn) => {
                btn.addEventListener("click", function () {
                    const nextIndex = (currentIndex + 1) % cards.length;
                    showCard(nextIndex, "next");
                    currentIndex = nextIndex;
                });
            });
        
            // Botón "Prev" (Anterior)
            document.querySelectorAll(".prev-btn").forEach((btn) => {
                btn.addEventListener("click", function () {
                    const prevIndex = (currentIndex - 1 + cards.length) % cards.length;
                    showCard(prevIndex, "prev");
                    currentIndex = prevIndex;
                });
            });
        
            // Inicializa la primera tarjeta
            showCard(currentIndex, "next");
        });
        </script>
        
</html>
