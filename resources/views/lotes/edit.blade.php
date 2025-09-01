@extends('template')

@section('title', 'Editar Lote')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="{{ asset('css/scanner.css') }}">
<link rel="stylesheet" href="{{ asset('css/lotecreate.css') }}">
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            <div class="card border-0 bg-white shadow-lg rounded-4 p-5" style="background-color: #f1f1f1ff;">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center fw-semibold text-dark">Editar Lote #{{ $loteEquipo->id }}</h4>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <form action="{{ route('lotes.update', $loteEquipo->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Imagen -->
                            <div class="col-12 text-center mb-4">
                                @if($loteEquipo->img_path)
                                    <img src="{{ asset('storage/' . $loteEquipo->img_path) }}" id="preview" class="img-fluid border rounded mb-3" style="max-width: 220px; background: #f8f9fa;">
                                @else
                                    <img id="preview" class="img-fluid border rounded mb-3" style="max-width: 220px; display:none; background: #f8f9fa;" alt="Vista previa">
                                @endif
                                <div>
                                    <label for="img_path" class="form-label fw-medium">Imagen del equipo</label>
                                    <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                                    @error('img_path')
                                    <small class="text-danger">{{'*'.$message}}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información -->
                            <div class="col-12">
                                <div class="row g-3">
                                    <!-- Modelo -->
                                    <div class="col-md-6">
                                        <label for="modelo" class="form-label fw-medium">Modelo</label>
                                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $loteEquipo->modelo) }}">
                                        @error('modelo')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <!-- Contenido de etiqueta -->
                                    <div class="col-md-6">
                                        <label for="contenido_etiqueta" class="form-label fw-medium">Contenido de etiqueta</label>
                                        <input type="text" name="contenido_etiqueta" id="contenido_etiqueta" class="form-control" value="{{ old('contenido_etiqueta', $loteEquipo->contenido_etiqueta) }}">
                                        @error('contenido_etiqueta')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <!-- Detalle -->
                                    <div class="col-md-6">
                                        <label for="detalle" class="form-label fw-medium">Detalle</label>
                                        <input type="text" name="detalle" id="detalle" class="form-control" value="{{ old('detalle', $loteEquipo->detalle) }}">
                                        @error('detalle')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>

                                    <!-- Marca -->
                                    <div class="col-md-6">
                                        <label for="marca_id" class="form-label fw-medium">Marca</label>
                                        <select name="marca_id" id="marca_id" class="form-select selectpicker show-tick" data-size="4" data-live-search="true">
                                            @foreach($marcas as $marca)
                                                <option value="{{ $marca->id }}" {{ old('marca_id', $loteEquipo->marca_id) == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                                            @endforeach
                                        </select>
                                        @error('marca_id')
                                        <small class="text-danger">{{'*'.$message}}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Categorías -->
                            <div class="col-md-12">
                                <label class="form-label fw-medium">Categorías</label>
                                <div class="border rounded p-3 bg-light" style="max-height: 120px; overflow-y: auto;">
                                    <div class="row" id="categorias-checkboxes">
                                        @foreach($categorias as $cat)
                                            <div class="col-md-4 col-6">
                                                <div class="form-check">
                                                    <input 
                                                        class="form-check-input" 
                                                        type="checkbox" 
                                                        name="categorias[]" 
                                                        id="cat_{{ $cat->id }}" 
                                                        value="{{ $cat->id }}" 
                                                        {{ in_array($cat->id, old('categorias', $loteEquipo->categorias->pluck('id')->toArray())) ? 'checked' : '' }}
                                                    >
                                                    <label class="form-check-label" for="cat_{{ $cat->id }}">
                                                        {{ $cat->nombre }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
                            <a href="{{ route('lotes.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-5">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
document.getElementById('img_path').addEventListener('change', function(e){
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    if (!file) {
        preview.style.display = 'none';
        preview.src = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(ev){
        preview.src = ev.target.result;
        preview.style.display = 'block';
    }
    reader.readAsDataURL(file);
});
</script>
@endpush
