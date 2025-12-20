<nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 backdrop-blur-lg border-b border-gray-100 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl gradient-bg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold gradient-text">Travelilly</span>
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}#features" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Features</a>
                <a href="{{ route('home') }}#destinations" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Destinations</a>
                <a href="{{ route('home') }}#how-it-works" class="nav-link text-gray-600 hover:text-gray-900 font-medium">How It Works</a>
                <a href="{{ route('home') }}#testimonials" class="nav-link text-gray-600 hover:text-gray-900 font-medium">Testimonials</a>
            </div>

            <!-- Download App Button -->
            <div class="flex items-center space-x-4">
                <a href="#download-app" class="btn-primary text-white px-6 py-2.5 rounded-full font-semibold text-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download App
                </a>
                
                <!-- Mobile Menu Button -->
                <button type="button" class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-3">
                <a href="{{ route('home') }}#features" class="text-gray-600 hover:text-gray-900 font-medium py-2">Features</a>
                <a href="{{ route('home') }}#destinations" class="text-gray-600 hover:text-gray-900 font-medium py-2">Destinations</a>
                <a href="{{ route('home') }}#how-it-works" class="text-gray-600 hover:text-gray-900 font-medium py-2">How It Works</a>
                <a href="{{ route('home') }}#testimonials" class="text-gray-600 hover:text-gray-900 font-medium py-2">Testimonials</a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    }
</script>
