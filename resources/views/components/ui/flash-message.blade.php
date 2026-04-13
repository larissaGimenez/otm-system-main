@php
    $alertTypes = [
        'success' => ['class' => 'alert-success', 'icon' => 'bi-check-circle-fill', 'color' => '#198754'],
        'error' => ['class' => 'alert-danger', 'icon' => 'bi-x-circle-fill', 'color' => '#dc3545'],
        'warning' => ['class' => 'alert-warning', 'icon' => 'bi-exclamation-triangle-fill', 'color' => '#ffc107'],
        'info' => ['class' => 'alert-info', 'icon' => 'bi-info-circle-fill', 'color' => '#0dcaf0'],
    ];
@endphp

@foreach ($alertTypes as $type => $config)
    @if (session($type))
        <div class="alert {{ $config['class'] }} alert-dismissible fade show shadow-sm border-0 d-flex align-items-center p-3 mb-4 custom-flash"
            role="alert" data-auto-dismiss="4000"
            style="border-left: 5px solid {{ $config['color'] }} !important; position: relative; overflow: hidden;">
            <i class="bi {{ $config['icon'] }} fs-4 me-3"></i>

            <div class="flex-grow-1 fw-bold text-dark" style="font-size: 0.95rem;">
                {{ session($type) }}
            </div>

            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>

            {{-- Barra de progresso animada via CSS --}}
            <div class="progress-bar-flash" style="background-color: {{ $config['color'] }}; opacity: 0.2;"></div>
        </div>
    @endif
@endforeach

<style>
    .custom-flash {
        background-color: #fff !important;
        /* Fundo branco deixa mais clean */
    }

    .progress-bar-flash {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        animation: shrink-progress 4s linear forwards;
    }

    @keyframes shrink-progress {
        from {
            width: 100%;
        }

        to {
            width: 0%;
        }
    }

    /* Ajuste para o botão de fechar não ficar em cima do texto em telas pequenas */
    @media (max-width: 576px) {
        .custom-flash {
            padding-right: 3.5rem !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Busca todos os alertas que têm o atributo de auto-dismiss
        const alerts = document.querySelectorAll('.custom-flash[data-auto-dismiss]');

        alerts.forEach(function (alert) {
            const timeout = alert.getAttribute('data-auto-dismiss');

            setTimeout(() => {
                // Usa a API nativa do Bootstrap para fechar
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            }, timeout);
        });
    });
</script>