@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 rounded-xl shadow-soft-sm focus:border-soft-primary focus:ring focus:ring-soft-primary focus:ring-opacity-30 text-sm']) }}>
