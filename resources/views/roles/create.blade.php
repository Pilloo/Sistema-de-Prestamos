@extends('template')


@section('title','Crear rol')


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Rol</h1>
  
    <!-- Mostrar mensaje de éxito -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <!-- Mostrar errores generales -->
    @if($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif


    <div class="card">
        <div class="card-header">
            <p>Nota: Los roles son un conjunto de permisos</p>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="post">
                @csrf


                <!-- Nombre del rol -->
                <div class="row mb-4">
                    <label for="name" class="col-md-auto col-form-label">Nombre del rol:</label>
                    <div class="col-md-4">
                        <input autocomplete="off" type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="col-md-4">
                        @error('name')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>


                <!-- Permisos -->
                <div class="mb-4">
                    <p class="text-muted">Permisos para el rol:</p>
                    <div class="row">
                        @foreach ($permisos->chunk(10) as $index => $chunk)
                            <div class="col-md-3 {{ $index !== 0 ? 'border-start border-light-subtle' : '' }}">
                                @foreach ($chunk as $item)
                                    <div class="form-check">
                                        <input type="checkbox" name="permission[]" id="permiso-{{ $item->id }}" class="form-check-input" value="{{ $item->id }}">
                                        <label for="permiso-{{ $item->id }}" class="form-check-label">{{ $item->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    @error('permission')
                    <small class="text-danger">{{ '*'.$message }}</small>
                    @enderror
                </div>


                <!-- Botón -->
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection


@push('js')
@endpush






