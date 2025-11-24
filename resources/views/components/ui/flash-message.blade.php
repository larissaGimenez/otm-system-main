@php
    $alertTypes = [
        'success' => 'alert-success',
        'error'   => 'alert-danger',
        'warning' => 'alert-warning',
        'info'    => 'alert-info',
    ];
@endphp

@foreach (['success', 'error', 'warning', 'info'] as $type)
    @if (session($type))
        <div 
            class="alert {{ $alertTypes[$type] }} alert-dismissible fade show d-flex align-items-center gap-2 mb-4"
            role="alert"
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 4000)"
        >
            <i class="bi 
                @if($type === 'success') bi-check-circle-fill
                @elseif($type === 'error') bi-x-circle-fill
                @elseif($type === 'warning') bi-exclamation-triangle-fill
                @elseif($type === 'info') bi-info-circle-fill
                @endif
            "></i>

            <span>{{ session($type) }}</span>

            <button 
                type="button" 
                class="btn-close ms-auto"
                aria-label="Close"
                @click="show = false"
            ></button>
        </div>
    @endif
@endforeach
