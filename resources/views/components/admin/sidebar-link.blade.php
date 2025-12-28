@props(['href', 'active' => false])

<a href="{{ $href }}"
   class="{{ $active
       ? 'bg-indigo-800 text-yellow-300'
       : 'hover:bg-indigo-600'
   }} block px-4 py-3 rounded-md text-sm font-medium transition duration-200 flex items-center">
    {{ $slot }}
</a>
