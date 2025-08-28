<!DOCTYPE html>
<html lang="es">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Solicitud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
      body {
        font-family: 'Inter', sans-serif;
        background: white;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
      }

      .card {
        border-radius: 1rem;
        border: 1px solid #c1c7d0;
        box-shadow: 0 0 20px rgb(0 0 0 / 0.1);
        max-width: 800px;
        width: 100%;
        padding: 1.5rem;
        position: relative;
        background-color: white;
      }

      .cart-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.25rem;
        color: black;
        transform: rotate(12deg);
      }

      .cart-badge {
        position: absolute;
        top: -0.25rem;
        right: -0.5rem;
        background-color: #ea801d;
        color: white;
        font-weight: 700;
        font-size: 10px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: Arial, sans-serif;
      }

      table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
      }

      thead th {
        border-bottom: 1px solid #9ca3af;
        padding-bottom: 0.5rem;
        text-align: left;
        font-weight: 500;
      }

      tbody tr {
        border-bottom: 1px solid #e5e7eb;
      }

      tbody td {
        padding: 0.5rem 0.25rem;
      }

      input[type="number"],
      input[type="text"],
      input[type="date"] {
        border: 1px solid #c1c7d0;
        border-radius: 0.5rem;
        padding: 0.4rem 0.6rem;
        font-size: 0.9rem;
        width: 100%;
        color: #333;
      }

      .btn-agregar {
        padding: 0.4rem 1rem;
        border: 2px solid #315cfd;
        border-radius: 30px;
        background: white;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s;
        color: #315cfd;
      }

      .btn-agregar:hover {
        background: #315cfd;
        color: white;
      }

      .btn-enviar {
        padding: 0.4rem 1rem;
        border: 2px solid #006b00;
        border-radius: 30px;
        background: white;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s;
        color: #006b00;
      }

      .btn-enviar:hover {
        background: #006b00;
        color: white;
      }

      .btn-vaciar {
        padding: 0.4rem 1rem;
        border: 2px solid #c50505;
        border-radius: 30px;
        background: white;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s;
        color: #c50505;
      }

      .btn-vaciar:hover {
        background: #c50505;
        color: white;
      }

      .btn-orange {
        background-color: #ea801d;
        color: white;
        font-weight: 700;
        font-size: 14px;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        border: none;
        line-height: 1;
        cursor: pointer;
      }

      .btn-orange:hover {
        background-color: #ea801d;
      }

      .empty-cart {
        text-align: center;
        padding: 2rem;
        color: #6b7280;
      }
    </style>
  </head>

  <body>
    <div class="card">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <h2 class="mb-4">Loan Request</h2>
      <div class="cart-icon">
        <i class="fas fa-shopping-cart"></i>
        <div class="cart-badge">{{ count($cart) }}</div>
      </div>

      @if (empty($cart))
        <div class="empty-cart">
          <p>Your cart is empty</p>
          <a href="{{ route('solicitud.create') }}" class="btn btn-agregar mt-2">
            <i class="fas fa-plus"></i> Add Equipment
          </a>
        </div>
      @else
        <table>
          <thead>
            <tr>
              <th>Equipment Name</th>
              <th>Quantity</th>
              <th>Due Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cart as $index => $item)
              <tr>
                <td>{{ $item['modelo'] }} ({{ $item['marca'] }})</td>
                <td>
                  <form action="{{ route('solicitud.updateCart', $index) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="number" name="cantidad" value="{{ $item['cantidad'] }}" min="1"
                      max="{{ $item['max_disponible'] }}" class="form-control form-control-sm">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                  </form>
                </td>
                <td>
                  <form action="{{ route('solicitud.updateCart', $index) }}" method="POST" class="d-flex gap-2">
                    @csrf
                    @method('PATCH')
                    <input type="date" name="fecha_limite" value="{{ $item['fecha_limite'] }}"
                      min="{{ date('Y-m-d') }}" class="form-control form-control-sm">
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                  </form>
                </td>
                <td>
                  <form action="{{ route('solicitud.removeFromCart', $index) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-orange" onclick="return confirm('Are you sure?')">X</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <form action="{{ route('solicitud.store') }}" method="POST" class="mt-4">
          @csrf
          <div class="mb-3">
            <label for="detalle" class="form-label">Request Details (Optional)</label>
            <textarea class="form-control" id="detalle" name="detalle" rows="2"
              placeholder="Additional details about your request"></textarea>
          </div>

          <div class="d-flex justify-content-between gap-2">
            <a href="{{ route('solicitud.create') }}" class="btn btn-agregar">
              <i class="fas fa-plus"></i> Add More
            </a>
            <button type="submit" class="btn btn-enviar">
              <i class="fas fa-paper-plane"></i> Submit Request
            </button>
            <a href="{{ route('solicitud.clearCart') }}" class="btn btn-vaciar"
              onclick="return confirm('Are you sure you want to clear your cart?')">
              <i class="fas fa-trash"></i> Clear Cart
            </a>
          </div>
        </form>
      @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>

</html>
