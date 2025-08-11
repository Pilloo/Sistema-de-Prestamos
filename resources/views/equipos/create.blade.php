@extends('template')

@section('title','Registro de Equipo')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/scanner.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 shadow-sm rounded-3" style="background-color: #f1f1f1ff;">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center fw-semibold text-dark">Registro de Nuevo Activo</h4>
                    <form action="{{ route('equipos.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Imagen -->
                            <div class="col-md-4 text-center">
                                <img id="preview" class="img-fluid border rounded mb-3" style="max-width: 220px; display: none; background: #f8f9fa;" alt="Vista previa">
                                <div>
                                    <label for="img_path" class="form-label fw-medium">Imagen del equipo</label>
                                    <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                                    @error('img_path')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información -->
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="equipo_id" class="form-label fw-medium">Equipo existente</label>
                                        <select name="equipo_id" id="equipo_id" class="form-select">
                                            <option value="">-- Registrar equipo nuevo --</option>
                                            @foreach ($equipos as $item)
                                            <option value="{{ $item->id }}">{{ $item->modelo }} ({{ $item->numero_serie }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="modelo" class="form-label fw-medium">Modelo</label>
                                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{old('modelo')}}">
                                        @error('modelo')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="numero_serie" class="form-label fw-medium">Número de serie</label>
                                        <input type="text" name="numero_serie" id="numero_serie" class="form-control" value="{{old('numero_serie')}}">
                                        @error('numero_serie')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="contenido_etiqueta" class="form-label fw-medium">Contenido de etiqueta</label>
                                        <input type="text" name="contenido_etiqueta" id="contenido_etiqueta" class="form-control" value="{{old('contenido_etiqueta')}}">
                                        @error('contenido_etiqueta')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="detalle" class="form-label fw-medium">Detalle</label>
                                        <input type="text" name="detalle" id="detalle" class="form-control" value="{{old('detalle')}}">
                                        @error('detalle')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label id="cantidad_label" for="cantidad_total" class="form-label fw-medium">Cantidad</label>
                                        <input type="number" name="cantidad_total" id="cantidad_total" class="form-control" min="1" value="{{old('cantidad_total')}}">
                                        @error('cantidad_total')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="marca_id" class="form-label fw-medium">Marca</label>
                                        <select name="marca_id" id="marca_id" class="form-select selectpicker show-tick" data-size="4" data-live-search="true">
                                            @foreach ($marcas as $item)
                                            <option value="{{$item->id}}" {{ old('marca_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                                            @endforeach
                                        </select>
                                        @error('marca_id')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="estado_equipo_id" class="form-label fw-medium">Estado</label>
                                        <select name="estado_equipo_id" id="estado_equipo_id" class="form-select selectpicker show-tick" data-size="4" data-live-search="true">
                                            @foreach ($estado_equipos as $item)
                                            <option value="{{$item->id}}" {{ old('estado_equipo_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                                            @endforeach
                                        </select>
                                        @error('estado_equipo_id')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-medium">Categorías</label>
                                        <div class="border rounded p-3 bg-light" style="max-height: 120px; overflow-y: auto;">
                                            <div class="row">
                                                @foreach ($categorias as $item)
                                                <div class="col-md-4 col-6">
                                                    <div class="form-check">
                                                        <input 
                                                            class="form-check-input" 
                                                            type="checkbox" 
                                                            name="categorias[]" 
                                                            id="categoria{{ $item->id }}" 
                                                            value="{{ $item->id }}"
                                                            {{ in_array($item->id, old('categorias', [])) ? 'checked' : '' }}
                                                        >
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
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                            <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                            <button type="button" class="btn btn-outline-dark px-4" onclick="openModal()"><i class="bi bi-camera"></i> Escanear con cámara</button>
                            <button type="button" class="btn btn-outline-dark px-4" onclick="openBarcodeModal()"><i class="bi bi-upc-scan"></i> Escanear con lector</button>
                            <button type="submit" class="btn btn-primary px-5">Guardar</button>
                        </div>

                        <!-- Modal Scanner -->
                        <div id="qrModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <h5>Escanear código QR</h5>
                                <div id="reader"></div>
                                <div id="result" class="mt-2"><em>Esperando escaneo...</em></div>
                            </div>
                        </div>

                        <!-- Modal Lector físico -->
                        <div id="barcodeModal" class="modal">
                            <div class="modal-content">
                                <span class="close" onclick="closeBarcodeModal()">&times;</span>
                                <h5>Escanear con lector de código</h5>
                                <input type="text" id="barcodeInput" class="form-control mt-2" placeholder="Escanee el código aquí">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="{{ asset('js/scanner.js') }}"></script>
<script src="{{ asset('js/scannerLector.js') }}"></script>

<script>
    const equiposData = @json($equiposCategorias);
    console.log(equiposData);
</script>

<script src="{{ asset('js/insertarEquipoRegistrado.js') }}"></script>
@endpush
