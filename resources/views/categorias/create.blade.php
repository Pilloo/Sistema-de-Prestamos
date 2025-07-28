<div class="modal fade" id="crearCategoriaModal" tabindex="-1" aria-labelledby="categoriaCrearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="categoriaForm" action="{{ route('categorias.store') }}" method="post">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="categoriaModalLabel">Agregar Categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreCategoria" class="form-label">Nombre de la categoría</label>
                        <input type="text" name="nombre" id="nombreCategoria" class="form-control" value="{{old('nombre')}}" placeholder="Ej: Tecnología">
                        @error('nombre')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
        </form>
      </div>
    </div>
  </div>