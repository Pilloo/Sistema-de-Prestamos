<div class="sidebar">
    <img class="logo" src="{{ asset('img\template\TI_BN.png') }}" alt="Logo TI">
    <a href="#" class="texto"><i class="bi bi-person img_tamano"></i><span class="TextoNav">Perfil</span></a>
    <a href="#" class="texto"><i class="bi bi-question-circle-fill"></i><span class="TextoNav">Ver Solicitudes</span></a>
    <a href="#" class="texto"><i class="bi bi-file-earmark-plus"></i><span class="TextoNav">Agregar Solicitudes</span></a>
    <a href="#" class="texto"><i class="bi bi-list-columns-reverse"></i><span class="TextoNav">Prestamos</span></a>
    <a href="{{ route('categorias.index')}}" class="texto" ><i class="bi bi-list-columns-reverse"></i>
        <span class="TextoNav">Categorias</span></a>
    </a>
    <a href="{{ route('marcas.index')}}" class="texto" ><i class="bi bi-list-columns-reverse"></i>
        <span class="TextoNav">Marcas</span></a>
    </a>
    <a href="{{ route('equipos.index')}}" class="texto" ><i class="bi bi-list-columns-reverse"></i>
        <span class="TextoNav">Equipos</span></a>
    </a>
</div>