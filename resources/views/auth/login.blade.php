<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login</title>

  <!-- Bootstrap y FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="{{ asset('css/login.css') }}" rel="stylesheet" />
</head>
<body>

  <div class="card-custom text-center">

    <img src="/img/template/TI_BN.png" alt="Logo" class="logo" />

    <!--Validacion de errores-->
    @if ($errors->any())
      @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show text-start" role="alert">
          {{ $error }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endforeach
    @endif

    <!--Validacion de errores-->
    <form method="POST" action="/login">
      @csrf

      <div class="mb-3 position-relative text-start">
        <i class="fas fa-user input-icon"></i>
        <input type="email" class="form-control" name="email" id="email" placeholder="Correo electrónico (name@example.com)" value="" required autofocus autocomplete="off">
      </div>

      <div class="mb-3 position-relative text-start">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
      </div>

      <div class="text-end mb-3">
        <a href="#" class="forgot-link">¿Olvidó su contraseña?</a>
      </div>

      <button type="submit" class="btn btn-blue">Iniciar sesión</button>
    </form>

    <p class="register-text mt-3">
      ¿No tiene una cuenta?
      <a href="{{ route('register')}}" class="register-link">Registrarse</a>
    </p>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
