@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="{{ $active ? 'text-yellow-300 border-b-2 border-yellow-300' : 'hover:text-yellow-200' }}
          px-3 py-2 text-sm font-medium transition duration-200">
    {{ $slot }}
</a>
