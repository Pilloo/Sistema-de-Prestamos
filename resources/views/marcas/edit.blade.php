@extends('template')

@section('title', 'Editar Marcas')

@push('css')
<link href="{{ asset('css/marcas.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="modal fade" id="crearMarcaModal" tabindex="-1" aria-labelledby="marcaCrearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="marcaForm" action="{{ route('marcas.update',['marca'=>$marca]) }}" method="post">
        @method('PATCH')
        @csrf  
            <div class="modal-header">
                <h5 class="modal-title" id="marcaModalLabel">Editar Marca</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="marcaId">
                <div class="mb-3">
                    <label for="nombreMarca" class="form-label">Nombre de la marca</label>
                    <input type="text" name="nombre" class="form-control" id="nombreMarca" 
                    value="{{old('nombre',$marca->caracteristica->nombre)}}" placeholder="Ej: Adidas">
                    @error('nombre')
                    <small class="text-danger">{{'*'.$message}}</small>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <button type="reset" class="btn btn-secondary">Actualizar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </form>
      </div>
    </div>
</div>

@endsection

@push('js')

@endpush