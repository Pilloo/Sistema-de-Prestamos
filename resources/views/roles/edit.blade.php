@extends('template')


@section('title','Editar rol')


@push('css')
@endpush


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Rol</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('roles.index')}}">Roles</a></li>
        <li class="breadcrumb-item active">Editar rol</li>
    </ol>


    <div class="card">
        <div class="card-header">
            <p>Nota: Los roles son un conjunto de permisos</p>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.update',['role'=>$role]) }}" method="post">
                @method('PATCH')
                @csrf


                <!-- Nombre del rol -->
                <div class="row mb-4">
                    <label for="name" class="col-md-auto col-form-label">Nombre del rol:</label>
                    <div class="col-md-4">
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $role->name) }}">
                    </div>
                    <div class="col-md-4">
                        @error('name')
                        <small class="text-danger">{{ '*'.$message }}</small>
                        @enderror
                    </div>
                </div>


                <!-- Permisos en columnas de 10 -->
                <div class="mb-4">
                    <p class="text-muted">Permisos para el rol:</p>
                    <div class="row">
                        @foreach ($permisos->chunk(10) as $index => $chunk)
                            <div class="col-md-3 {{ $index !== 0 ? 'border-start border-light-subtle' : '' }}">
                                @foreach ($chunk as $item)
                                    <div class="form-check mb-2">
                                        <input
                                            type="checkbox"
                                            name="permission[]"
                                            id="permiso-{{ $item->id }}"
                                            class="form-check-input"
                                            value="{{ $item->id }}"
                                            {{ in_array($item->id, $role->permissions->pluck('id')->toArray()) ? 'checked' : '' }}
                                        >
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


                <!-- Botones -->
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="reset" class="btn btn-secondary">Reiniciar</button>
                </div>


            </form>
        </div>
    </div>
</div>
@endsection


@push('js')
@endpush




