<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Plyr Video Player -->
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.polyfilled.js"></script>
    </head>
    <body class="font-sans antialiased text-soft-dark bg-soft-bg relative flex h-screen">
        
        <!-- Sidebar Navigation -->
        <livewire:layout.navigation />

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col h-screen overflow-y-auto relative">
            <!-- Header -->
            <header class="h-20 px-8 flex items-center justify-between flex-shrink-0 z-10">
                <div>
                    @if (isset($header))
                        {{ $header }}
                    @else
                        <h1 class="text-2xl font-bold text-soft-dark">Pipeline Control</h1>
                    @endif
                </div>
                <div class="flex items-center space-x-6">
                    <livewire:notification-bell />
                    @auth
                    <div class="hidden sm:flex items-center space-x-2 border-l border-gray-300 pl-6">
                        <span class="text-xs font-bold text-gray-500 tracking-widest uppercase">CURRENT SESSION:</span>
                        <span class="text-sm font-extrabold text-soft-dark uppercase">{{ auth()->user()->role_level == 1 ? 'ADMIN' : (auth()->user()->role_level == 3 ? 'REVIEWER' : 'PRODUCTION') }}</span>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-8 pt-2 pb-32">
                {{ $slot }}
            </main>
        </div>

        <!-- Global Toast Notifications -->
        <div x-data="{ toasts: [] }" 
             @notify.window="
                let id = Date.now();
                toasts.push({ id: id, message: $event.detail.message });
                setTimeout(() => { toasts = toasts = toasts.filter(t => t.id !== id) }, 3000);
             "
             class="fixed bottom-5 right-5 z-50 flex flex-col space-y-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="true"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="bg-gray-900 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center border-l-4 border-green-500 min-w-[300px]">
                    <svg class="w-6 h-6 mr-3 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span x-text="toast.message" class="font-medium text-sm"></span>
                </div>
            </template>
        </div>
    </body>
</html>
