<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registro</title>

  <!-- Bootstrap y FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background-image: url("/img/template/fondoPrincipal.jpg");
      background-size: cover;
      background-position: center center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .form-check-input:checked {
      background-color: #ea801d;
      border-color: #ea801d;
    }

    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(234, 128, 29, 0.25);
      border-color: #ea801d;
    }

    .card-custom {
      background-color: #c4c4c4;
      padding: 2rem 2.5rem;
      border-radius: 15px;
      max-width: 550px;
      width: 100%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-control, .form-select {
      padding-left: 2.5rem;
      height: 2.75rem;
      font-size: 1rem;
      border-radius: 0.375rem;
    }

    .input-icon {
      position: absolute;
      left: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      color: #000;
      font-size: 1.1rem;
    }

    .btn-orange {
      padding: 0.5rem 2rem;
      border: 2px solid #ea801d;
      border-radius: 30px;
      background: white;
      font-size: 1rem;
      font-weight: 500;
      transition: all 0.3s;
      color: black;
    }

    .btn-orange:hover {
      background: #ea801d;
      color: white;
    }

    .p {
      text-align: center;
      font-size: 2pc;
    }

    .register-text {
      font-weight: 600;
      font-size: 0.875rem;
      margin-top: 1rem;
      text-align: center;
      color: #000;
    }

    .register-link {
      font-weight: 700;
      color: #ea801d;
      text-decoration: none;
    }

    .register-link:hover {
      text-decoration: underline;
    }

    .form-check-label {
      margin-left: 0.5rem;
    }

    @media (max-width: 767px) {
      .card-custom {
        max-width: 100%;
      }
    }
  </style>
</head>
<body>


@if(session('success'))
<script>
Swal.fire({
  toast: true,
  position: "top-end",
  icon: "success",
  title: "{{ session('success') }}",
  showConfirmButton: false,
  timer: 1500,
  timerProgressBar: true
});
</script>
@endif
@if(session('error'))
<script>
Swal.fire({
  icon: 'error',
  title: 'Error',
  text: "{{ session('error') }}",
  confirmButtonText: 'Cerrar'
});
</script>
@endif

@if ($errors->any())
<script>
Swal.fire({
  icon: 'error',
  title: 'Error de validación',
  html: `{!! implode('<br>', $errors->all()) !!}`,
  confirmButtonText: 'Cerrar'
});
</script>
@endif

<div class="card-custom">
  <p class="p">Registro</p>

  <!-- Radio para elegir el tipo de registro -->
  <div class="mt-3 d-flex justify-content-center">
    <div class="form-check me-4">
      <input class="form-check-input" type="radio" name="rol" id="funcionario" value="funcionario" checked />
      <label class="form-check-label" for="funcionario">Funcionario</label>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="radio" name="rol" id="estudiante" value="estudiante" />
      <label class="form-check-label" for="estudiante">Estudiante</label>
    </div>
  </div>

  <!-- Formulario -->
  <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" id="form-registro">
    @csrf
    <div class="row g-3 mt-3">
      <div class="col-md-6 position-relative text-start">
        <i class="fas fa-user input-icon"></i>
        <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Nombre" required />
      </div>
      <div class="col-md-6 position-relative text-start">
        <i class="fas fa-user input-icon"></i>
        <input type="text" class="form-control" name="primer_apellido" value="{{ old('primer_apellido') }}" placeholder="Primer Apellido" />
      </div>
      <div class="col-md-6 position-relative text-start">
        <i class="fas fa-user input-icon"></i>
        <input type="text" class="form-control" name="segundo_apellido" value="{{ old('segundo_apellido') }}" placeholder="Segundo Apellido" />
      </div>
    </div>

    <div class="mt-3 position-relative text-start">
      <i class="fas fa-envelope input-icon"></i>
      <input type="email" class="form-control" name="email" placeholder="Correo electrónico" required />
    </div>

    <!-- Departamento -->
    <div class="mt-3 position-relative text-start" id="select-departamento">
      <i class="fas fa-building input-icon"></i>
      <select class="form-select" name="departamento_id" id="departamento_id" required>
        <option selected disabled>Seleccione Departamento</option>
        @isset($departamentos)
          @foreach ($departamentos as $item)
            <option value="{{$item->id}}" @selected(old('departamento_id') == $item->id)>{{$item->nombre}}</option>
          @endforeach
        @endisset
      </select>
      @error('departamento_id')
      <small class="text-danger d-block mt-1">{{'*'.$message}}</small>
      @enderror
    </div>

    <!-- Sección -->
    <div class="mt-3 position-relative text-start" id="select-seccion" style="display: none;">
      <i class="fas fa-school input-icon"></i>
      <select class="form-select" name="seccion_id" id="seccion_id" required>
        <option selected disabled>Seleccione Sección</option>
        @isset($secciones)
          @foreach ($secciones as $item)
            <option value="{{$item->id}}" @selected(old('seccion_id') == $item->id)>{{$item->nombre}}</option>
          @endforeach
        @endisset
      </select>
      @error('seccion_id')
      <small class="text-danger d-block mt-1">{{'*'.$message}}</small>
      @enderror
    </div>

    <div class="mt-3 position-relative text-start">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" class="form-control" name="password" placeholder="Contraseña" required />
    </div>
    <small class="text-muted d-block mt-1" style="margin-left:2.5rem;">La contraseña debe tener mínimo 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial (@$!%*?&).</small>

    <div class="mt-3 position-relative text-start">
      <i class="fas fa-lock input-icon"></i>
      <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmar Contraseña" required />
    </div>

    <div class="text-center mt-4">
      <button type="submit" class="btn btn-orange">Registrar</button>
    </div>
  </form>

  <p class="register-text mt-3">
    ¿Ya tiene una cuenta?
    <a href="{{ route('login') }}" class="register-link">Iniciar sesión</a>
  </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('form-registro').addEventListener('submit', function(e) {
    var pass = document.querySelector('input[name="password"]').value;
    var passConfirm = document.querySelector('input[name="password_confirmation"]').value;
    if (pass !== passConfirm) {
      e.preventDefault();
      Swal.fire({
        icon: 'error',
        title: 'Las contraseñas no coinciden',
        text: 'Por favor, asegúrate de que ambas contraseñas sean iguales.',
        confirmButtonText: 'Cerrar'
      });
    }
  });
</script>
<script>
  const estudianteRadio = document.getElementById("estudiante");
  const funcionarioRadio = document.getElementById("funcionario");
  const selectDepartamento = document.getElementById("select-departamento");
  const selectSeccion = document.getElementById("select-seccion");

  estudianteRadio.addEventListener("change", () => {
    selectSeccion.style.display = "block";
    selectDepartamento.style.display = "none";
  });

  funcionarioRadio.addEventListener("change", () => {
    selectSeccion.style.display = "none";
    selectDepartamento.style.display = "block";
  });
</script>

</body>
</html>
