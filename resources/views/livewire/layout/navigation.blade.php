<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-72 bg-white h-screen shadow-soft flex flex-col justify-between border-r border-gray-100 flex-shrink-0 z-20">
    <!-- Top part -->
    <div>
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-gray-50">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3">
                <x-application-logo class="block h-9 w-auto fill-current text-soft-dark" />
                <span class="text-lg font-bold text-soft-dark">{{ config('app.name', 'Laravel') }}</span>
            </a>
        </div>

        <!-- Nav Links -->
        <nav class="p-4 space-y-2 mt-4">
            {{-- Dashboard - visible to all --}}
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-gradient-to-tl from-gray-800 to-gray-600 text-white shadow-soft rounded-xl' : 'text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            
            {{-- Admin Only (Level 1) --}}
            @if(auth()->user()->role_level == 1)
            <a href="{{ route('manage-tasks') }}" wire:navigate class="flex items-center px-4 py-3 {{ request()->routeIs('manage-tasks') ? 'bg-gradient-to-tl from-gray-800 to-gray-600 text-white shadow-soft rounded-xl' : 'text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <span class="font-semibold text-sm">Manage Tasks</span>
            </a>
            
            <a href="{{ route('staff-directory') }}" wire:navigate class="flex items-center px-4 py-3 {{ request()->routeIs('staff-directory') ? 'bg-gradient-to-tl from-gray-800 to-gray-600 text-white shadow-soft rounded-xl' : 'text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="font-semibold text-sm">Staff Directory</span>
            </a>
            @endif
            
            {{-- Production (Level 2) - My Tasks --}}
            @if(auth()->user()->role_level == 2)
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <span class="font-semibold text-sm">My Submissions</span>
            </a>
            @endif

            {{-- Reviewer (Level 3) - Review Queue --}}
            @if(auth()->user()->role_level == 3)
            <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="font-semibold text-sm">Review Queue</span>
            </a>
            @endif

            {{-- Settings - visible to all --}}
            <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-4 py-3 {{ request()->routeIs('profile') ? 'bg-gradient-to-tl from-gray-800 to-gray-600 text-white shadow-soft rounded-xl' : 'text-gray-500 hover:bg-gray-50 hover:text-soft-dark rounded-xl transition' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="font-semibold text-sm">Settings</span>
            </a>
        </nav>
    </div>

    <!-- Bottom Profile -->
    <div class="p-4 mb-4">
        <button wire:click="logout" class="w-full flex items-center p-3 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition shadow-sm group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-tl from-purple-700 to-pink-500 text-white flex items-center justify-center font-bold shadow-soft">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="ml-3 text-left overflow-hidden">
                <p class="text-sm font-bold text-soft-dark truncate" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></p>
                <p class="text-xs text-gray-400 capitalize truncate">{{ str_replace('_', ' ', auth()->user()->role_specialty ?? 'Super Admin') }}</p>
            </div>
            <div class="ml-auto text-gray-300 group-hover:text-red-500 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            </div>
        </button>
    </div>
</aside>
