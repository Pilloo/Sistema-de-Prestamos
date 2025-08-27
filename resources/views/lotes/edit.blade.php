@extends('template')

@section('title', 'Editar Lote')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Editar Lote #{{ $loteEquipo->id }}</h3>
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <form action="{{ route('lotes.update', $loteEquipo->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-4 text-center">
                @if($loteEquipo->img_path)
                    <img src="{{ asset('storage/' . $loteEquipo->img_path) }}" class="img-fluid border rounded mb-3" style="max-width: 220px; background: #f8f9fa;" alt="Imagen actual">
                @endif
                <div>
                    <label for="img_path" class="form-label fw-medium">Imagen del equipo</label>
                    <input type="file" name="img_path" id="img_path" class="form-control" accept="image/*">
                    @error('img_path')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="modelo" class="form-label fw-medium">Modelo</label>
                        <input type="text" name="modelo" id="modelo" class="form-control" value="{{ old('modelo', $loteEquipo->modelo) }}">
                        @error('modelo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="contenido_etiqueta" class="form-label fw-medium">Contenido de etiqueta</label>
                        <input type="text" name="contenido_etiqueta" id="contenido_etiqueta" class="form-control" value="{{ old('contenido_etiqueta', $loteEquipo->contenido_etiqueta) }}">
                        @error('contenido_etiqueta')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="detalle" class="form-label fw-medium">Detalle</label>
                        <input type="text" name="detalle" id="detalle" class="form-control" value="{{ old('detalle', $loteEquipo->detalle) }}">
                        @error('detalle')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="marca_id" class="form-label fw-medium">Marca</label>
                        <select name="marca_id" id="marca_id" class="form-select selectpicker show-tick" data-size="4" data-live-search="true">
                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}" {{ $loteEquipo->marca_id == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                        @error('marca_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-medium">Categorías</label>
                        <div>
                            @foreach($categorias as $cat)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="categorias[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}" {{ $loteEquipo->categorias->contains($cat->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3 justify-content-center mt-4">
            <a href="{{ route('lotes.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
            <button type="submit" class="btn btn-primary px-5">Actualizar</button>
        </div>
    </form>
</div>
@endsection
