@props([
    'id',
    'active' => false,
])

<div 
    class="tab-pane fade {{ $active ? 'show active' : '' }}" 
    id="{{ $id }}" 
    role="tabpanel" 
    tabindex="0"
>
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>