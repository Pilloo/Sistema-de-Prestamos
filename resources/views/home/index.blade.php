@extends('template')

@section('title','Home')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

#homeContent {
    background-color: #f0f4f8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #333;
}

.principal {
    background-color: #ffffff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
    max-width: 700px;
    text-align: center;
}

.grande {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

.boton {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: #ffffff;
    font-weight: 500;
    font-size: 16px;
    text-decoration: none;
    border-radius: 12px;
    box-shadow: 0 6px 12px rgba(0, 123, 255, 0.3);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    margin: 10px;
}

.boton i {
    font-size: 28px;
    margin-bottom: 8px;
}

.boton:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 18px rgba(0, 123, 255, 0.4);
    text-decoration: none;
    color: #e0f0ff;
}

.grande > a:nth-child(-n+3) {
    flex: 0 0 30%;
}

.grande > a:nth-child(n+4) {
    flex: 0 0 35%;
}

</style>

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

<body id="homeContent">
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
