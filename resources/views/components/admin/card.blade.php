@props(['icon', 'title', 'value', 'color' => 'teal'])

@php
    $bgColor = [
        'teal' => 'bg-teal-500',
        'blue' => 'bg-blue-500',
        'green' => 'bg-green-500',
    ][$color] ?? 'bg-teal-500';
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
