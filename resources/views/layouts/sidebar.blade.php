<nav id="sidebar" class="d-flex flex-column p-3 bg-white border-end shadow-sm">
    
    <ul class="nav nav-pills flex-column mb-auto">
        
        <li class="nav-item mb-1">
            <a class="nav-link text-dark {{ request()->routeIs('home') ? 'active text-white' : '' }}" 
               style="{{ request()->routeIs('home') ? 'background-color: var(--bs-primary);' : '' }}" 
               href="{{ route('home') }}">
                <i class="bi bi-house-door-fill me-2"></i> Home
            </a>
        </li>
        
        <hr class="text-secondary-emphasis">

        @if (Auth::user()->hasAnyRole(['admin', 'manager', 'staff']))
            <div class="px-2 mb-2 small text-uppercase fw-bold text-muted">Operacional</div>
            
            <li class="nav-item mb-1">
                {{-- MUDANÇA: Adicionada a classe 'collapsed' por padrão --}}
                <a class="nav-link text-dark dropdown-toggle collapsed" href="#pdvSubmenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-shop me-2"></i> Pontos de Venda
                </a>
                <div class="collapse" id="pdvSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Listar</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Criar um novo</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                {{-- MUDANÇA: Adicionada a classe 'collapsed' por padrão --}}
                <a class="nav-link text-dark dropdown-toggle collapsed" href="#chamadosSubmenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-headset me-2"></i> Chamados
                </a>
                <div class="collapse" id="chamadosSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Listar</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Criar um novo</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->hasAnyRole(['admin', 'manager']))
            <hr class="text-secondary-emphasis">
            <div class="px-2 mb-2 small text-uppercase fw-bold text-muted">Gestão</div>

            <li class="nav-item mb-1">
                {{-- MUDANÇA: Adicionada a classe 'collapsed' por padrão --}}
                <a class="nav-link text-dark dropdown-toggle collapsed" href="#equipesSubmenu" data-bs-toggle="collapse" role="button">
                    <i class="bi bi-people-fill me-2"></i> Grupos
                </a>
                <div class="collapse" id="equipesSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Listar</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-dark small py-1">Criar um novo</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                {{-- MUDANÇA: A classe 'collapsed' é omitida aqui, pois o menu já deve estar aberto --}}
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('management.users.*') ? '' : 'collapsed' }} {{ request()->routeIs('management.users.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('management.users.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#usuariosSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('management.users.*') ? 'true' : 'false' }}">
                    <i class="bi bi-person-lines-fill me-2"></i> Usuários
                </a>
                <div class="collapse {{ request()->routeIs('management.users.*') ? 'show' : '' }}" id="usuariosSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="{{ route('management.users.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.users.index') ? 'fw-bold' : '' }}">
                                Listar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('management.users.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.users.create') ? 'fw-bold' : '' }}">
                                Criar um novo
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
    </ul>

    <hr class="text-secondary-emphasis">
    <div class="small px-2">
        <strong>{{ Auth::user()->getRoleName() }}</strong><br>
        {{ Auth::user()->name }}
    </div>
</nav>