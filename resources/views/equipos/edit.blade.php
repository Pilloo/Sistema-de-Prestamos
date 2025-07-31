@extends('template')

@section('title','Editar Equipo')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/scanner.css') }}">
@endpush

@section('content')

<div class="container mt-5">
    <h3 class="mb-4 text-center">Editar Activo</h3>
    <form class="p-4 border rounded bg-light" action="{{ route('equipos.update', $equipo->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        @if ($equipo->img_path)
            <div class="mt-3">
                <strong>Imagen actual:</strong>
                <div>
                    <img src="{{ asset('img/equipos/' . $equipo->img_path) }}" alt="Imagen equipo" 
                        class="img-thumbnail mt-2" 
                        style="width: 300px; height: 200px; object-fit: cover;">
                </div>
            </div>
        @else
            <p class="mt-3"><em>No hay imagen disponible.</em></p>
        @endif


        <div class="col-md-6">
            <label for="img_path" class="form-label">Cambiar imagen:</label>
            <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
            @error('img_path')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6 mb-2">
            <label for="modelo" class="form-label">Modelo:</label>
            <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $equipo->modelo) }}">
            @error('modelo')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6 mb-2">
            <label for="numero_serie" class="form-label">Número Serie:</label>
            <input type="text" name="numero_serie" id="numero_serie" class="form-control" value="{{ old('numero_serie', $equipo->numero_serie) }}">
            @error('numero_serie')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6 mb-2">
            <label for="contenido_etiqueta" class="form-label">Contenido Etiqueta:</label>
            <input type="text" name="contenido_etiqueta" id="contenido_etiqueta" class="form-control" value="{{ old('contenido_etiqueta', $equipo->contenido_etiqueta) }}">
            @error('contenido_etiqueta')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6 mb-2">
            <label for="detalle" class="form-label">Detalle:</label>
            <input type="text" name="detalle" id="detalle" class="form-control" value="{{ old('detalle', $equipo->detalle) }}">
            @error('detalle')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Categorías:</label>

            <div class="border rounded p-3" style="max-height: 100px; overflow-y: auto;">
                <div class="row">
                    @foreach ($categorias as $item)
                    <div class="col-md-4 col-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="categorias[]" id="categoria{{ $item->id }}"
                                value="{{ $item->id }}"
                                {{ in_array($item->id, old('categorias', $equipo->categorias->pluck('id')->toArray())) ? 'checked' : '' }}>
                            <label class="form-check-label" for="categoria{{ $item->id }}">
                                {{ $item->nombre }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @error('categorias')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="marca_id" class="form-label">Marca:</label>
            <select data-size="4" title="Seleccione una marca" data-live-search="true" name="marca_id" id="marca_id" class="form-control selectpicker show-tick">
                @foreach ($marcas as $item)
                <option value="{{ $item->id }}" {{ old('marca_id', $equipo->marca_id) == $item->id ? 'selected' : '' }}>{{ $item->nombre }}</option>
                @endforeach
            </select>
            @error('marca_id')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="estado_equipo_id" class="form-label">Estado del equipo:</label>
            <select data-size="4" title="Seleccione un estado" data-live-search="true" name="estado_equipo_id" id="estado_equipo_id" class="form-control selectpicker show-tick">
                @foreach ($estado_equipos as $item)
                <option value="{{ $item->id }}" {{ old('estado_equipo_id', $equipo->estado_equipo_id) == $item->id ? 'selected' : '' }}>{{ $item->nombre }}</option>
                @endforeach
            </select>
            @error('estado_equipo_id')
            <small class="text-danger">{{ '*'.$message }}</small>
            @enderror
        </div>

        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('equipos.index') }}" class="btn btn-secondary">Volver</a>
            <button type="button" class="btn" onclick="openModal()">Escanear con cámara</button>
            <button type="button" class="btn" onclick="openBarcodeModal()">Escanear con lector</button>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>

        <!-- Modal Scanner Bootstrap -->
        <div id="qrModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h3>Escanear QR</h3>
                <div id="reader"></div>
                <div id="result">Resultado: <em>Esperando escaneo...</em></div>
            </div>
        </div>

        <!-- Modal para escáner físico -->
        <div id="barcodeModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeBarcodeModal()">&times;</span>
                <h3>Escanear con lector de código</h3>
                <input type="text" id="barcodeInput" class="form-control" placeholder="Escanee el código aquí">
            </div>
        </div>

    </form>
</div>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('js/scanner.js') }}"></script>
<script src="{{ asset('js/scannerLector.js') }}"></script>
@endpush
