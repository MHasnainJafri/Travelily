<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Travelilly - Plan your perfect journey together. Create jamboards, connect with friends, hire local guides and hosts.">
    <meta name="keywords" content="travel, trip planning, jamboard, travel guides, travel hosts, travel app">
    
    <title>@yield('title', 'Travelilly - Plan Your Perfect Journey')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/css/frontend.css'])
    
    @stack('styles')
</head>
<body class="frontend-body antialiased bg-white">
    <!-- Navigation -->
    @include('components.frontend.navbar')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- App Download Section -->
    @include('components.frontend.app-download')
    
    <!-- Footer -->
    @include('components.frontend.footer')
    
    <!-- Scripts -->
    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md', 'bg-white');
                nav.classList.remove('bg-white/80');
            } else {
                nav.classList.remove('shadow-md', 'bg-white');
                nav.classList.add('bg-white/80');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
