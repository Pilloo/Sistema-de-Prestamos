<div class="sidebar">
    <!-- Logo -->
    <a href="#"><img src="{{ asset('img/template/TI_BN.png') }}" class="logo" alt="logo ti"></a>

    <!-- Bienvenida al usuario -->
    <a class="texto">
        <span class="TextoNav">Bienvenido:</span>
        {{ auth()->check() ? auth()->user()->name : 'Invitado' }}
    </a>

    <!-- Enlaces principales -->
    <a href="#" class="texto">
        <i class="bi bi-person"></i><span class="TextoNav">Perfil</span>
    </a>

    <!-- Enlaces con permisos -->
    @can('ver solicitudes')
    <a href="#" class="texto">
        <i class="bi bi-question-circle-fill"></i><span class="TextoNav">Solicitudes</span>
    </a>
    @endcan

    @can('ver prestamos')
    <a href="#" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Préstamos</span>
    </a>
    @endcan

    <!-- Gestión de objetos -->
    @can('ver categorias')
    <a href="{{ route('categorias.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Categorías</span>
    </a>
    @endcan

    @can('ver marcas')
    <a href="{{ route('marcas.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Marcas</span>
    </a>
    @endcan

    @can('ver equipos')
    <a href="{{ route('equipos.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Equipos</span>
    </a>
    @endcan

    <!-- Gestión de roles y usuarios -->
    @can('ver roles')
    <a href="{{ route('roles.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Roles</span>
    </a>
    @endcan

    @can('ver usuarios')
    <a href="{{ route('users.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Usuarios</span>
    </a>
    @endcan

    <!-- Gestión de departamentos y secciones -->
    @can('ver departamentos')
    <a href="{{ route('departamentos.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Departamentos</span>
    </a>
    @endcan

    @can('ver secciones')
    <a href="{{ route('secciones.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Secciones</span>
    </a>
    @endcan

    <a href="{{ route('lotes.index')}}" class="texto">
        <i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Lotes </span>
    </a>

    <!-- Cerrar sesión -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href="#" class="texto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-right"></i><span class="TextoNav">Cerrar sesión</span>
    </a>
</div>
