<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" :messages="$errors->get('name')" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" :messages="$errors->get('email')" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password" :value="__('Password')" />
            {{-- MUDANÇA: Adicionado Input Group para o ícone --}}
            <div class="input-group">
                <x-text-input id="password"
                                type="password"
                                name="password"
                                required autocomplete="new-password" 
                                :messages="$errors->get('password')" />
                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="bi bi-eye-slash-fill"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="mb-3">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
             {{-- MUDANÇA: Adicionado Input Group para o ícone --}}
            <div class="input-group">
                <x-text-input id="password_confirmation"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                :messages="$errors->get('password_confirmation')" />
                <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirmation">
                    <i class="bi bi-eye-slash-fill"></i>
                </button>
            </div>
             {{-- MUDANÇA: Adicionado espaço para a mensagem de confirmação --}}
            <div id="password-match-status" class="form-text mt-1"></div>
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="d-flex align-items-center justify-content-end mt-4">
            <a class="text-decoration-underline text-muted small" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-3">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>