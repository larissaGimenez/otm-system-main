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
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('clients.*') ? '' : 'collapsed' }} {{ request()->routeIs('clients.*') ? 'active text-white' : '' }}"
                style="{{ request()->routeIs('clients.*') ? 'background-color: var(--bs-primary);' : '' }}"
                href="#clientSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('clients.*') ? 'true' : 'false' }}">
                    <i class="bi bi-people-fill me-2"></i> Clientes
                </a>
                <div class="collapse {{ request()->routeIs('clients.*') ? 'show' : '' }}" id="clientSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="{{ route('clients.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('clients.index') ? 'fw-bold' : '' }}">
                                Listar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('clients.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('clients.create') ? 'fw-bold' : '' }}">
                                Criar um novo
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            
            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('pdvs.*') ? '' : 'collapsed' }} {{ request()->routeIs('pdvs.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('pdvs.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#pdvSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('pdvs.*') ? 'true' : 'false' }}">
                    <i class="bi bi-shop me-2"></i> Pontos de Venda
                </a>
                <div class="collapse {{ request()->routeIs('pdvs.*') ? 'show' : '' }}" id="pdvSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item"><a href="{{ route('pdvs.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('pdvs.index') ? 'fw-bold' : '' }}">Listar</a></li>
                        <li class="nav-item"><a href="{{ route('pdvs.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('pdvs.create') ? 'fw-bold' : '' }}">Criar um novo</a></li>
                        <li class="nav-item"><a href="{{ route('settings.pdv.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('settings.pdv.*') ? 'fw-bold' : '' }}">Configurações</a></li>
                    </ul>
                </div>
            </li>

            

            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('requests.*') ? '' : 'collapsed' }} {{ request()->routeIs('requests.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('requests.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#chamadosSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('requests.*') ? 'true' : 'false' }}">
                    <i class="bi bi-headset me-2"></i> Chamados
                </a>
                <div class="collapse {{ request()->routeIs('requests.*') ? 'show' : '' }}" id="chamadosSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item">
                            <a href="{{ route('requests.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('requests.index') ? 'fw-bold' : '' }}">
                                Listar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('requests.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('requests.create') ? 'fw-bold' : '' }}">
                                Abrir um novo
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif

        @if (Auth::user()->hasAnyRole(['admin', 'manager']))
            <hr class="text-secondary-emphasis">
            <div class="px-2 mb-2 small text-uppercase fw-bold text-muted">Gestão</div>

            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('management.teams.*') ? '' : 'collapsed' }} {{ request()->routeIs('management.teams.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('management.teams.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#equipesSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('management.teams.*') ? 'true' : 'false' }}">
                    <i class="bi bi-people-fill me-2"></i> Equipes
                </a>
                <div class="collapse {{ request()->routeIs('management.teams.*') ? 'show' : '' }}" id="equipesSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item"><a href="{{ route('management.teams.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.teams.index') ? 'fw-bold' : '' }}">Listar</a></li>
                        <li class="nav-item"><a href="{{ route('management.teams.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.teams.create') ? 'fw-bold' : '' }}">Criar um novo</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('areas.*') ? '' : 'collapsed' }} {{ request()->routeIs('areas.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('areas.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#areasSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('areas.*') ? 'true' : 'false' }}">
                    <i class="bi bi-collection-fill me-2"></i> Áreas
                </a>
                <div class="collapse {{ request()->routeIs('areas.*') ? 'show' : '' }}" id="areasSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item"><a href="{{ route('areas.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('areas.index') ? 'fw-bold' : '' }}">Listar</a></li>
                        <li class="nav-item"><a href="{{ route('areas.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('areas.create') ? 'fw-bold' : '' }}">Criar uma nova</a></li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('management.users.*') ? '' : 'collapsed' }} {{ request()->routeIs('management.users.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('management.users.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#usuariosSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('management.users.*') ? 'true' : 'false' }}">
                    <i class="bi bi-person-lines-fill me-2"></i> Usuários
                </a>
                <div class="collapse {{ request()->routeIs('management.users.*') ? 'show' : '' }}" id="usuariosSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item"><a href="{{ route('management.users.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.users.index') ? 'fw-bold' : '' }}">Listar</a></li>
                        <li class="nav-item"><a href="{{ route('management.users.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('management.users.create') ? 'fw-bold' : '' }}">Criar um novo</a></li>
                    </ul>
                </div>
            </li>

            <hr class="text-secondary-emphasis">
            <div class="px-2 mb-2 small text-uppercase fw-bold text-muted">Configurações</div>

            <li class="nav-item mb-1">
                <a class="nav-link text-dark dropdown-toggle {{ request()->routeIs('equipments.*') ? '' : 'collapsed' }} {{ request()->routeIs('equipments.*') ? 'active text-white' : '' }}"
                   style="{{ request()->routeIs('equipments.*') ? 'background-color: var(--bs-primary);' : '' }}"
                   href="#equipamentosSubmenu" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('equipments.*') ? 'true' : 'false' }}">
                    <i class="bi bi-hdd-stack-fill me-2"></i> Equipamentos
                </a>
                <div class="collapse {{ request()->routeIs('equipments.*') ? 'show' : '' }}" id="equipamentosSubmenu">
                    <ul class="nav flex-column ps-4 pt-1">
                        <li class="nav-item"><a href="{{ route('equipments.index') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('equipments.index') ? 'fw-bold' : '' }}">Listar</a></li>
                        <li class="nav-item"><a href="{{ route('equipments.create') }}" class="nav-link text-dark small py-1 {{ request()->routeIs('equipments.create') ? 'fw-bold' : '' }}">Criar um novo</a></li>
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