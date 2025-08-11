<div class="modal fade" id="crearDepartamentoModal" tabindex="-1" aria-labelledby="departamentoCrearModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="departamentoForm" action="{{ route('departamentos.store') }}" method="post">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="departamentoModalLabel">Agregar Departamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombreDepartamento" class="form-label">Nombre del departamento</label>
                        <input type="text" name="nombre" id="nombreDepartamento" class="form-control" value="{{old('nombre')}}" placeholder="Ej: Administración">
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