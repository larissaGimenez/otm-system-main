// 1. Importações: Todas as bibliotecas são importadas no topo.
import './bootstrap';
// import Alpine from 'alpinejs'; // <-- 1. REMOVIDO: Livewire 3 já inclui o Alpine.
import IMask from 'imask';
import * as bootstrap from 'bootstrap';
import Sortable from 'sortablejs';

// 2. Globais: Disponibilizamos as bibliotecas globalmente.
window.bootstrap = bootstrap;
// window.Alpine = Alpine; // <-- 2. REMOVIDO: Não é mais necessário.
window.IMask = IMask; 

// 3. Inicialização: Alpine.js é iniciado APENAS UMA VEZ.
// Alpine.start(); // <-- 3. REMOVIDO: Livewire 3 inicia o Alpine automaticamente.

// 4. Lógica Customizada: Todo o seu código fica dentro de um ÚNICO listener.
document.addEventListener('DOMContentLoaded', function () {

    // --- LÓGICA DO MENU ---
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapses = document.querySelectorAll('#sidebarCollapse, #sidebarCollapseMobile');
    if (sidebar && sidebarCollapses.length) {
        sidebarCollapses.forEach(button => {
            button.addEventListener('click', function () {
                sidebar.classList.toggle('active');
            });
        });
    }
    
    // --- LÓGICA PARA MOSTRAR/ESCONDER SENHA ---
    const setupPasswordToggle = (inputId, toggleId) => {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(toggleId);
        if (passwordInput && toggleButton) {
            toggleButton.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
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

// Esta parte está PERFEITA para o Livewire
document.addEventListener('livewire:navigated', () => {
    // Verifique se estamos na página Kanban
    const columns = document.querySelectorAll('.kanban-column-body');
    if (columns.length > 0) {
        initializeKanban();
    }
});

// Esta função também está PERFEITA
function initializeKanban() {
    const columns = document.querySelectorAll('.kanban-column-body');
    
    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban-requests', // Permite mover entre colunas com o mesmo grupo
            animation: 150,
            ghostClass: 'kanban-ghost-card', // Classe CSS para o "fantasma" do card

            // A MÁGICA ACONTECE AQUI
            onEnd: function (evt) {
                // O card que foi movido
                const item = evt.item; 
                // A coluna para onde ele foi
                const toColumn = evt.to; 

                // Pega os data-attributes que definimos no HTML
                const requestId = item.dataset.id;
                const newStatus = toColumn.dataset.status;

                // Chama o método 'handleStatusUpdate' no backend Livewire
                // Usando o @this (que o Livewire injeta)
                const livewireComponent = Livewire.find(
                    item.closest('[wire\\:id]').getAttribute('wire:id')
                );
                
                if (livewireComponent) {
                    livewireComponent.call('handleStatusUpdate', requestId, newStatus);
                }
            },
        });
    });
}