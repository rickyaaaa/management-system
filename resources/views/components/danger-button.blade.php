<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-red-500 border border-transparent rounded-xl font-bold text-sm text-white shadow-soft-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition']) }}>
    {{ $slot }}
</button>
