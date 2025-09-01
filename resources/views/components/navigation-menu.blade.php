<div class="sidebar">
    
   

    <a href="{{ url('/') }}" class="texto">
    <i class="fas fa-home"></i><span class="TextoNav">Home</span>
    </a>
     

    <!-- Perfil -->
    <a href="{{ auth()->check() ? route('users.show', auth()->user()->id) : '#' }}" class="texto">
        <i class="fas fa-user"></i><span class="TextoNav">Perfil</span>
    </a>

     <!-- Usuarios -->
    @can('ver usuarios')
    <a href="{{ route('users.index')}}" class="texto">
        <i class="fas fa-users"></i><span class="TextoNav">Usuarios</span>
    </a>
    @endcan

    <!-- Roles -->
    @can('ver roles')
    <a href="{{ route('roles.index')}}" class="texto">
        <i class="fas fa-user-shield"></i><span class="TextoNav">Roles</span>
    </a>
    @endcan


       <!-- Crear Solicitud -->
    @can('crear solicitudes')
    <a href="{{ route('solicitud.create') }}" class="texto">
        <i class="fa-solid fa-circle-question"></i><span class="TextoNav">Solicitar Préstamo</span>
    </a>
    @endcan

    <!-- Solicitudes -->
    @can('ver solicitudes')
    <a href="{{ route('solicitud.index') }}" class="texto">
        <i class="fas fa-clipboard-list"></i><span class="TextoNav">Prestamos</span>
    </a>
    @endcan

      <!-- Lotes -->
    @can('ver lotes')
    <a href="{{ route('lotes.index')}}" class="texto">
        <i class="fas fa-boxes-stacked"></i><span class="TextoNav">Lotes</span>
    </a>
    @endcan


    <!-- Equipos -->
    @can('ver equipos')
    <a href="{{ route('equipos.index')}}" class="texto">
        <i class="fas fa-laptop"></i><span class="TextoNav">Equipos</span>
    </a>
    @endcan


    <!-- Inventario -->
    @can('ver inventario')
    <a href="{{ route('equipos.inventario')}}" class="texto">
        <i class="fas fa-box-open"></i><span class="TextoNav">Inventario</span>
    </a>
    @endcan

   

    <!-- Departamentos -->
    @can('ver departamentos')
    <a href="{{ route('departamentos.index')}}" class="texto">
        <i class="fas fa-building"></i><span class="TextoNav">Departamentos</span>
    </a>
    @endcan

    <!-- Secciones -->
    @can('ver secciones')
    <a href="{{ route('secciones.index')}}" class="texto">
        <i class="fas fa-th-large"></i><span class="TextoNav">Secciones</span>
    </a>
    @endcan
      <!-- Categorías -->
    @can('ver categorias')
    <a href="{{ route('categorias.index')}}" class="texto">
        <i class="fas fa-tags"></i><span class="TextoNav">Categorías</span>
    </a>
    @endcan
       <!-- Marcas -->
    @can('ver marcas')
    <a href="{{ route('marcas.index')}}" class="texto">
        <i class="fas fa-trademark"></i><span class="TextoNav">Marcas</span>
    </a>
    @endcan
    

    <br>
    
    <!-- Cerrar sesión -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <a href="#" class="texto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="fas fa-sign-out-alt"></i><span class="TextoNav">Cerrar sesión</span>
    </a>
</div>