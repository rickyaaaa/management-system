<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-6 py-2.5 bg-gradient-to-tl from-purple-700 to-pink-500 hover:from-purple-600 hover:to-pink-400 text-white font-bold rounded-xl shadow-soft-sm hover:shadow-soft transition text-sm']) }}>
    {{ $slot }}
</button>
