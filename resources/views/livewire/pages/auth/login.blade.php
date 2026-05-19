<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold tracking-tight text-gray-950">
            Welcome User
        </h2>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login" class="space-y-6">
        <!-- Username -->
        <div>
            <label for="username" class="block text-sm font-medium leading-6 text-gray-950">
                Username
            </label>
            <div class="mt-2">
                <input wire:model="form.username" id="username" type="text" name="username" required autofocus autocomplete="username" 
                    class="block w-full rounded-lg border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
            </div>
            <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-gray-950">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a href="{{ route('password.request') }}" wire:navigate class="font-semibold text-amber-600 hover:text-amber-500">
                            Forgot password?
                        </a>
                    </div>
                @endif
            </div>
            <div class="mt-2">
                <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password" 
                    class="block w-full rounded-lg border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-amber-500 sm:text-sm sm:leading-6">
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input wire:model="form.remember" id="remember" type="checkbox" name="remember" 
                class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-600">
            <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900">
                Remember me
            </label>
        </div>

        <div>
            <button type="submit" 
                class="flex w-full justify-center rounded-lg bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-600 transition">
                Sign in
            </button>
        </div>
    </form>
</div>
