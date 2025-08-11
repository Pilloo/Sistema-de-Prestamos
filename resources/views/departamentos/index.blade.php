@extends('template')

@section('title','Departamentos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
  title: message // ← CORRECTO (sin punto y coma)
});
</script>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<body class="ContenidoPrincipal">
    <div id="contenedorDepartamentos" class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Listado de Departamentos</h2>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#crearDepartamentoModal">Agregar Departamento</button>
        </div>

        <div class="mb-3">
            <input type="text" id="placeholderDepartamentos" class="form-control" id="buscador" placeholder="Buscar departamento...">
        </div>

        <div id="contenedorTablaDepartamentos">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                    <th>#</th>
                    <th>Departamento</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="departamentoBody">
                    @foreach($departamentos as $departamento)
                        <tr>
                            <td>{{ $departamento->id }}</td>    
                            <td>{{ $departamento->caracteristica->nombre }}</td>
                            <td>
                                @if ($departamento->caracteristica->estado == 1)
                                    <span class="badge rounded-pill text-bg-success">Activo</span>
                                @else
                                    <span class="badge rounded-pill text-bg-danger">Eliminado</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarDepartamentoModal-{{ $departamento->id }}">Editar</button>
                                    @if ($departamento->caracteristica->estado == 1)
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $departamento->id }}">Eliminar</button>
                                    @else
                                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#confirmModal-{{ $departamento->id }}">Restaurar</button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal editar departamento -->
                        <div class="modal fade" id="editarDepartamentoModal-{{ $departamento->id }}" tabindex="-1" aria-labelledby="departamentoModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form id="departamentoForm" action="{{ route('departamentos.update',['departamento'=>$departamento]) }}" method="post">
                                        @method('PATCH')
                                        @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="departamentoModalLabel">Agregar Departamento</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nombreDepartamento" class="form-label">Nombre del departamento</label>
                                                    <input type="text" name="nombre" id="nombreDepartamento" class="form-control" value="{{old('nombre',$departamento->caracteristica->nombre)}}">
                                                    @error('nombre')
                                                    <small class="text-danger">{{'*'.$message}}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Actualizar</button>
                                                <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>

@include('departamentos.create')

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush