@extends('template')

@section('title','Home')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="{{ asset('css/home.css') }}" rel="stylesheet" />


@endpush

@section('content')

@if(session('success'))
<script>
// Para recuperar el mensaje que quiero que muestre el TOAST
let message = "{{ session('success') }}";


const Toast = Swal.mixin({
  toast: true,
  position: "top-end",
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.onmouseenter = Swal.stopTimer;
    toast.onmouseleave = Swal.resumeTimer;
  }
});
Toast.fire({
  icon: "success",
  title: message
});
</script>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<body id="homeContent" style="background: url('{{ asset('img/template/fondoPrincipal.jpg') }}') no-repeat center center fixed; background-size: cover;">
    <div class="container">
        <div class="card principal mx-auto">
            <div class="card-body">
                <h2 class="mb-4">Inicio Técnico</h2>
                <div class="grande">
                    <a href="#perfil" class="boton">
                        <i class="fas fa-user"></i>
                        Perfil
                    </a>
                    <a href="#usuarios" class="boton">
                        <i class="fas fa-users"></i>
                        Usuarios
                    </a>
                    <a href="#solicitudes" class="boton">
                        <i class="fa-solid fa-circle-question"></i>
                        Solicitudes
                    </a>
                    <a href="#prestamos" class="boton">
                        <i class="fas fa-clipboard-list"></i>
                        Préstamos
                    </a>
                    <a href="#inventario" class="boton">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush
