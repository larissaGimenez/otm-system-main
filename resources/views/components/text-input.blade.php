@props(['disabled' => false, 'messages' => []])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-control' . ($messages ? ' is-invalid' : '')]) !!}>