@props(['icon', 'title', 'value', 'color' => 'indigo'])

@php
    $bgColor = [
        'indigo' => 'bg-indigo-500',
        'yellow' => 'bg-yellow-500',
        'green' => 'bg-green-500',
    ][$color] ?? 'bg-indigo-500';
@endphp

<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-600">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-800">{{ $value }}</p>
        </div>
        <div class="{{ $bgColor }} text-white p-4 rounded-full">
            <i class="{{ $icon }} text-xl"></i>
        </div>
    </div>
</div>
