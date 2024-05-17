<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Navigation Links -->
            <ul class="navbar-nav me-2">
                <li class="nav-item">
                    <button class="btn btn-outline-light btn-sm" onclick="window.location='{{ route('dashboard') }}'">{{ __('Dashboard') }}</button>
                </li>
            </ul>
            @auth
            <ul class="navbar-nav me-2">
                <li class="nav-item">
                    <button id="toggleFiltro" class="dropdown-toggle btn btn-outline-light btn-sm">Busca Rapida </button>
                </li>
            </ul>
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <button id="toggleBusca" class="dropdown-toggle btn btn-outline-light btn-sm">Busca Complexa </button>
                </li>
            </ul>
            <ul class="navbar-nav me-2">
                <li class="nav-item">
                    <button id="adicionarDocumento" class="btn btn-outline-light btn-sm">Adicionar Documento </button>
                </li>
            </ul>
            <ul class="navbar-nav me-2">
                <li class="nav-item">
                    <button id="fecharDocumento" class="btn btn-outline-danger btn-sm">Fechar Documento</button>
                </li>
            </ul>
            @endauth

            <!-- Settings Dropdown -->
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="btn btn-outline-light btn-sm dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ optional(Auth::user())->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item text-black" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item text-black" type="submit">{{ __('Log Out') }}</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>