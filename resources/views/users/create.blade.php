@extends('template')


@section('title','Crear usuario')


@push('css')


@endpush


@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Crear Usuario</li>
    </ol>


    <div class="card text-bg-light">
    <form action="{{ route('users.store') }}" method="post" enctype="multipart/form-data">
                <!---Primer Apellido---->
                <div class="row mb-4">
                    <label for="primer_apellido" class="col-lg-2 col-form-label">Primer Apellido:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="primer_apellido" id="primer_apellido" class="form-control" value="{{old('primer_apellido')}}">
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-2">
                        @error('primer_apellido')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Segundo Apellido---->
                <div class="row mb-4">
                    <label for="segundo_apellido" class="col-lg-2 col-form-label">Segundo Apellido:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="segundo_apellido" id="segundo_apellido" class="form-control" value="{{old('segundo_apellido')}}">
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-2">
                        @error('segundo_apellido')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>
            @csrf
            <div class="card-header">
                <p class="">Nota: Los usuarios son los que pueden ingresar al sistema</p>
            </div>
            <div class="card-body">


                <!---Nombre---->
                <div class="row mb-4">
                    <label for="name" class="col-lg-2 col-form-label">Nombres:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="name" id="name" class="form-control" value="{{old('name')}}" aria-labelledby="nameHelpBlock">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="nameHelpBlock">
                            Escriba un solo nombre
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('name')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>


                <!---Email---->
                <div class="row mb-4">
                    <label for="email" class="col-lg-2 col-form-label">Email:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="email" name="email" id="email" class="form-control" value="{{old('email')}}" aria-labelledby="emailHelpBlock">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="emailHelpBlock">
                            Dirección de correo eléctronico
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>


                <!---Password---->
                <div class="row mb-4">
                    <label for="password" class="col-lg-2 col-form-label">Contraseña:</label>
                    <div class="col-lg-4">
                        <input type="password" name="password" id="password" class="form-control" aria-labelledby="passwordHelpBlock">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordHelpBlock">
                            Escriba una constraseña segura. Debe incluir números.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>


                <!---Confirm_Password---->
                <div class="row mb-4">
                    <label for="password_confirm" class="col-lg-2 col-form-label">Confirmar:</label>
                    <div class="col-lg-4">
                        <input type="password" name="password_confirm" id="password_confirm" class="form-control" aria-labelledby="passwordConfirmHelpBlock">
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordConfirmHelpBlock">
                            Vuelva a escribir su contraseña.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password_confirm')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>



                <!---Departamento---->
                <div class="row mb-4">
                    <label for="departamento_id" class="col-lg-2 col-form-label">Departamento:</label>
                    <div class="col-lg-4">
                        <select name="departamento_id" id="departamento_id" class="form-select">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($departamentos as $item)
                            <option value="{{$item->id}}" @selected(old('departamento_id')==$item->id)>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-2">
                        @error('departamento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Sección---->
                <div class="row mb-4">
                    <label for="seccion_id" class="col-lg-2 col-form-label">Sección:</label>
                    <div class="col-lg-4">
                        <select name="seccion_id" id="seccion_id" class="form-select">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($secciones as $item)
                            <option value="{{$item->id}}" @selected(old('seccion_id')==$item->id)>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-2">
                        @error('seccion_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Roles---->
                <div class="row mb-4">
                    <label for="role" class="col-lg-2 col-form-label">Rol:</label>
                    <div class="col-lg-4">
                        <select name="role" id="role" class="form-select" aria-labelledby="rolHelpBlock">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($roles as $item)
                            <option value="{{$item->name}}" @selected(old('role')==$item->name)>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="rolHelpBlock">
                            Escoja un rol para el usuario.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('role')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <!---Imagen---->
                <div class="row mb-4">
                    <label for="img_path" class="col-lg-2 col-form-label">Imagen:</label>
                    <div class="col-lg-4">
                        <input type="file" name="img_path" id="img_path" class="form-control">
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-2">
                        @error('img_path')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>


            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>




</div>
@endsection


@push('js')


@endpush


