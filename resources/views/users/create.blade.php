@extends('template')

@section('title', 'Crear usuario')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
<link href="{{ asset('css/users.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="form-container">
    <div class="user-icon">
      <i class="fas fa-user-plus"></i>
    </div>
    <h4 class="text-center mb-4">Crear Usuario</h4>

    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row g-3">
        
        {{-- Nombre --}}
        <div class="col-md-6">
          <label for="name" class="form-label small">Nombres</label>
          <input type="text" name="name" id="name" class="input-editable" value="{{ old('name') }}">
          @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Primer Apellido --}}
        <div class="col-md-6">
          <label for="primer_apellido" class="form-label small">Primer Apellido</label>
          <input type="text" name="primer_apellido" id="primer_apellido" class="input-editable" value="{{ old('primer_apellido') }}">
          @error('primer_apellido') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Segundo Apellido --}}
        <div class="col-md-6">
          <label for="segundo_apellido" class="form-label small">Segundo Apellido</label>
          <input type="text" name="segundo_apellido" id="segundo_apellido" class="input-editable" value="{{ old('segundo_apellido') }}">
          @error('segundo_apellido') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Email --}}
        <div class="col-md-6">
          <label for="email" class="form-label small">Email</label>
          <input type="email" name="email" id="email" class="input-editable" value="{{ old('email') }}">
          @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Contraseña --}}
        <div class="col-md-6">
          <label for="password" class="form-label small">Contraseña</label>
          <input type="password" name="password" id="password" class="input-editable">
          @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Confirmar Contraseña --}}
        <div class="col-md-6">
          <label for="password_confirm" class="form-label small">Confirmar Contraseña</label>
          <input type="password" name="password_confirm" id="password_confirm" class="input-editable">
          @error('password_confirm') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Departamento --}}
        <div class="col-md-6">
          <label for="departamento_id" class="form-label small">Departamento</label>
          <select name="departamento_id" id="departamento_id" class="input-editable">
            <option value="" disabled selected>Seleccione:</option>
            @foreach ($departamentos as $item)
              <option value="{{ $item->id }}" @selected(old('departamento_id') == $item->id)>{{ $item->nombre }}</option>
            @endforeach
          </select>
          @error('departamento_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Sección --}}
        <div class="col-md-6">
          <label for="seccion_id" class="form-label small">Sección</label>
          <select name="seccion_id" id="seccion_id" class="input-editable">
            <option value="" disabled selected>Seleccione:</option>
            @foreach ($secciones as $item)
              <option value="{{ $item->id }}" @selected(old('seccion_id') == $item->id)>{{ $item->nombre }}</option>
            @endforeach
          </select>
          @error('seccion_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Rol --}}
        <div class="col-md-6">
          <label for="role" class="form-label small">Rol</label>
          <select name="role" id="role" class="input-editable">
            <option value="" disabled selected>Seleccione:</option>
            @foreach ($roles as $item)
              <option value="{{ $item->name }}" @selected(old('role') == $item->name)>{{ $item->name }}</option>
            @endforeach
          </select>
          @error('role') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        {{-- Imagen --}}
        <div class="col-md-6">
          <label for="img_path" class="form-label small">Imagen</label>
          <input type="file" name="img_path" id="img_path" class="input-editable">
          @error('img_path') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
      </div>

      {{-- Botones --}}
      <div class="d-flex justify-content-between mt-4">
        <a href="{{ route('users.index') }}" class="btn btn-custom">
          <i class="fas fa-arrow-left"></i> Volver
        </a>
        <button type="submit" class="btn btn-custom">
          <i class="fas fa-save"></i> Guardar
        </button>
      </div>

    </form>
  </div>
</div>
@endsection



@push('js')
@endpush
