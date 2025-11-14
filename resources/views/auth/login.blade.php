<x-guest-layout>
    {{-- Isso exibe mensagens como "link de redefinição de senha enviado" --}}
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" :messages="$errors->get('email')" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-group">
                <x-text-input id="password"
                              type="password"
                              name="password"
                              required autocomplete="current-password"
                              :messages="$errors->get('password')" />
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash-fill"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                <label for="remember_me" class="form-check-label text-muted small">
                    {{ __('Remember me') }}
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-underline text-muted small" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="d-grid">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        {{-- O Bloco de Registro foi removido daqui --}}

    </form>
</x-guest-layout>