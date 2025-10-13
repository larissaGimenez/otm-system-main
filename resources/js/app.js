import './bootstrap';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import Alpine from 'alpinejs';
import IMask from 'imask';

window.Alpine = Alpine;
window.IMask = IMask; 

Alpine.start();

document.addEventListener('DOMContentLoaded', function () {

    // --- LÓGICA PARA MOSTRAR/ESCONDER SENHA ---
    const setupPasswordToggle = (inputId, toggleId) => {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(toggleId);

        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function () {
                // Alterna o tipo do input
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Alterna o ícone do botão
                const icon = this.querySelector('i');
                icon.classList.toggle('bi-eye-slash-fill');
                icon.classList.toggle('bi-eye-fill');
            });
        }
    };

    setupPasswordToggle('password', 'togglePassword');
    setupPasswordToggle('password_confirmation', 'togglePasswordConfirmation');


    // --- LÓGICA PARA VERIFICAR SE AS SENHAS SÃO IGUAIS ---
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const matchStatusDiv = document.getElementById('password-match-status');

    if (passwordInput && passwordConfirmInput && matchStatusDiv) {
        const validatePasswords = () => {
            const pass = passwordInput.value;
            const confirmPass = passwordConfirmInput.value;

            if (confirmPass.length === 0) {
                matchStatusDiv.textContent = ''; 
                return;
            }

            if (pass === confirmPass) {
                matchStatusDiv.textContent = 'As senhas coincidem!';
                matchStatusDiv.className = 'form-text mt-1 text-success';
            } else {
                matchStatusDiv.textContent = 'As senhas não coincidem!';
                matchStatusDiv.className = 'form-text mt-1 text-danger';
            }
        };

        passwordInput.addEventListener('input', validatePasswords);
        passwordConfirmInput.addEventListener('input', validatePasswords);
    }
});

// menu
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapses = document.querySelectorAll('#sidebarCollapse, #sidebarCollapseMobile');

    if (sidebar && sidebarCollapses.length) {
        sidebarCollapses.forEach(button => {
            button.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });
        });
    }
});