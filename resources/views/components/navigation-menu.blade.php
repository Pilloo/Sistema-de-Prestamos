<div class="sidebar">
    <a href="#"><img src="{{ asset('img/template/TI_BN.png') }}" class="logo" alt="logo ti"></a>
    <a class="texto"><span class="TextoNav">Bienvenido:</span>
        {{ auth()->check() ? auth()->user()->name : 'Invitado' }}
    </a>
    <a href="#" class="texto"><i class="bi bi-person"></i><span class="TextoNav">Perfil</span></a>
    <a href="#" class="texto"><i class="bi bi-people"></i><span class="TextoNav">Usuarios</span></a>
    <a href="#" class="texto"><i class="bi bi-question-circle-fill"></i><span class="TextoNav">Solicitudes</span></a>
    <a href="#" class="texto"><i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Préstamos</span></a>
    <a href="{{ route('categorias.index')}}" class="texto"><i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Categorías</span></a>
    <a href="{{ route('marcas.index')}}" class="texto"><i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Marcas</span></a>
    <a href="{{ route('equipos.index')}}" class="texto"><i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Equipos</span></a>

</div>
