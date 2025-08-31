
<header class="header-bar d-flex align-items-center justify-content-between px-4 py-2" style="background-color: #343a40; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: fixed; top: 0; left: 0; right: 0; z-index: 100; height: 70px;">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/template/TI_BN.png') }}" alt="Logo HHC" class="logo" style="width:80px; height:auto;">
        </a>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="d-flex align-items-center gap-2">
            <i class="fas fa-user-circle fa-lg"></i>
            <span class="fw-semibold">¡Bienvenido {{ auth()->check() ? auth()->user()->name : 'Invitado' }}!</span>
        </span>
    </div>
</header>
