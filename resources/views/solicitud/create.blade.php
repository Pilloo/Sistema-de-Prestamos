@extends('template')

@section('title','Solicitar Préstamo de Equipos')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
<link href="{{ asset('css/solicitud.css') }}" rel="stylesheet" />

@endpush

@section('content')
<body>
    <div class="container py-4">
        <script>
            window.equipmentCart = @json(session('equipment_cart', []));
        </script>
        <div id="scannerErrorMsg" class="alert alert-danger d-none" role="alert"></div>
            <script>
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                };
            </script>
        <div class="card border-0 shadow-lg rounded-4 p-4" style="background-color: #f1f1f1ff;">
            <div class="card-body">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
                    <h3 class="fw-semibold mb-3 mb-md-0">Solicitar Préstamo de Equipos</h3>
                    <a href="{{ route('solicitud.cart') }}" class="btn btn-outline-primary position-relative">
                        <i class="fas fa-shopping-cart"></i> Ver Carrito
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ count(session('equipment_cart', [])) }}
                        </span>
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="mb-4 d-flex flex-wrap gap-3 align-items-end justify-content-center">
                    <div class="flex-grow-1">
                        <label for="scannerInput" class="form-label fw-medium">Escanear código</label>
                        <div class="input-group">
                            <input type="text" id="scannerInput" class="form-control" placeholder="Escanea el código de serie...">
                            <button type="button" class="btn btn-outline-dark" onclick="openModal()"><i class="bi bi-camera"></i> Escanear con cámara</button>
                        </div>
                    </div>
                <!-- Modal Scanner -->
                <style>
                #qrModal {
                    display: none;
                    position: fixed;
                    z-index: 9999;
                    left: 0;
                    top: 0;
                    width: 100vw;
                    height: 100vh;
                    background: rgba(0,0,0,0.4);
                    justify-content: center;
                    align-items: center;
                }
                #qrModal .modal-content {
                    background: #fff;
                    border-radius: 1rem;
                    max-width: 400px;
                    width: 90vw;
                    margin: auto;
                    padding: 2rem 1.5rem;
                    box-shadow: 0 4px 24px rgba(0,0,0,0.15);
                    position: relative;
                    top: 10vh;
                }
                #qrModal .close {
                    position: absolute;
                    right: 1rem;
                    top: 1rem;
                    font-size: 1.5rem;
                    cursor: pointer;
                }
                #reader {
                    width: 100%;
                    min-height: 220px;
                    margin-bottom: 1rem;
                }
                </style>
                <div id="qrModal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <h5 class="mb-3">Escanear código QR</h5>
                        <div id="reader"></div>
                        <div id="result" class="mt-2"><em>Esperando escaneo...</em></div>
                    </div>
                </div>
                    <div>
                        <label for="filtroCategoria" class="form-label fw-medium">Categoría</label>
                        <select id="filtroCategoria" class="form-select">
                            <option value="">Todas</option>
                            @php
                                $categorias = collect($equiposAgrupados)->pluck('categoria')->unique();
                            @endphp
                            @foreach($categorias as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="filtroMarca" class="form-label fw-medium">Marca</label>
                        <select id="filtroMarca" class="form-select">
                            <option value="">Todas</option>
                            @php
                                $marcas = collect($equiposAgrupados)->pluck('marca')->unique();
                            @endphp
                            @foreach($marcas as $marca)
                                <option value="{{ $marca }}">{{ $marca }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-grow-1">
                        <label for="buscador" class="form-label fw-medium">Buscar</label>
                        <input type="text" id="buscador" class="form-control" placeholder="Modelo, etiqueta">
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-4 justify-content-center" id="equiposContainer">
                    @foreach($equiposAgrupados as $grupo)
                    <div class="product-card equipo-item"
                        data-categoria="{{ $grupo['categoria'] }}"
                        data-marca="{{ $grupo['marca'] }}"
                        data-modelo="{{ $grupo['modelo'] }}"
                        data-etiqueta="{{ $grupo['lotes'][0]->contenido_etiqueta ?? '' }}"
                        data-serial="{{ $grupo['lotes'][0]->serial ?? '' }}">
                        @php $primerLote = $grupo['lotes'][0]; @endphp
                        <img src="{{ asset('img/equipos/' . $primerLote->img_path) }}" 
                            alt="{{ $grupo['modelo'] }}">
                        <div class="product-card-body">
                            <h5 class="text-truncate mb-1 fw-semibold">{{ $grupo['modelo'] }}</h5>
                            <p class="text-muted mb-1">Categoría: {{ $grupo['categoria'] }}</p>
                            <p class="text-muted mb-1">Marca: {{ $grupo['marca'] }}</p>
                            <p class="text-muted mb-3">Disponibles: {{ $grupo['cantidad_disponible'] }}</p>

                            @if($grupo['cantidad_disponible'] > 0)
                            <button type="button" class="btn btn-request open-modal"
                                    data-bs-toggle="modal" data-bs-target="#cartModal"
                                    data-lotes='@json($grupo['lotes'])'
                                    data-title="{{ $grupo['modelo'] }}"
                                    data-max="{{ $grupo['cantidad_disponible'] }}">
                                Solicitar
                            </button>
                            @else
                            <button type="button" class="btn btn-not-available" disabled>No disponible</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4">
                <form action="{{ route('solicitud.addToCart') }}" method="POST" id="addToCartForm">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold" id="cartModalLabel">Agregar al Carrito</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">¿Deseas agregar <span id="modalProductTitle" class="fw-bold">este equipo</span> a tu solicitud?</p>

                        <div class="mb-3">
                            <label for="loteSelect" class="form-label fw-medium">Selecciona Lote</label>
                            <select class="form-select" id="loteSelect" name="lote_id" required>
                                <!-- Opciones generadas por JS -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantityInput" class="form-label fw-medium">Cantidad</label>
                            <input type="number" class="form-control" id="quantityInput" name="cantidad" min="1" value="1" required>
                            <small class="text-muted">Máximo disponible: <span id="maxQuantity">0</span></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-medium">Selecciona equipo(s) por serial</label>
                            <div id="equipoCheckboxes" class="d-flex flex-wrap gap-2">
                                <!-- Checkboxes generados por JS -->
                            </div>
                            <small class="text-muted">Solo se muestran equipos disponibles en el lote.</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-5">Agregar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
@endsection

@push('js')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filtro y buscador
    const equiposContainer = document.getElementById('equiposContainer');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const filtroMarca = document.getElementById('filtroMarca');
    const buscador = document.getElementById('buscador');
    const scannerInput = document.getElementById('scannerInput');

    function filtrarEquipos() {
        const categoria = filtroCategoria.value.toLowerCase();
        const marca = filtroMarca.value.toLowerCase();
        const busqueda = buscador.value.toLowerCase();
        equiposContainer.querySelectorAll('.equipo-item').forEach(function(card) {
            const cat = card.getAttribute('data-categoria').toLowerCase();
            const mar = card.getAttribute('data-marca').toLowerCase();
            const modelo = card.getAttribute('data-modelo').toLowerCase();
            const etiqueta = card.getAttribute('data-etiqueta').toLowerCase();
            let visible = true;
            if (categoria && cat !== categoria) visible = false;
            if (marca && mar !== marca) visible = false;
            if (busqueda && !(modelo.includes(busqueda) || etiqueta.includes(busqueda))) visible = false;
            card.style.display = visible ? '' : 'none';
        });
    }
    filtroCategoria.addEventListener('change', filtrarEquipos);
    filtroMarca.addEventListener('change', filtrarEquipos);
    buscador.addEventListener('input', filtrarEquipos);

    // Modal lógica original mejorada para equipos por serial
const cartModal = document.getElementById('cartModal');
cartModal.addEventListener('show.bs.modal', function(event) {
    const button = event.relatedTarget;
    const lotes = JSON.parse(button.getAttribute('data-lotes'));
    const title = button.getAttribute('data-title');
    let maxQuantity = 0;

    // Obtener IDs de equipos y lotes en el carrito
    const cartEquipos = (window.equipmentCart || []).flatMap(item => Array.isArray(item.equipo_id) ? item.equipo_id : [item.equipo_id]);
    const cartLotes = (window.equipmentCart || []).map(item => item.lote_id);

    // Generar opciones de lotes en el select, excluyendo lotes completos si todos sus equipos están en el carrito
    const loteSelect = document.getElementById('loteSelect');
    loteSelect.innerHTML = '';
    lotes.forEach(function(lote) {
        // Filtrar equipos disponibles en el lote, excluyendo los que están en el carrito
        const equiposDisponibles = (lote.equipos ?? []).filter(equipo => 
            equipo.estado_equipo && equipo.estado_equipo.nombre.toLowerCase() === 'disponible' &&
            !cartEquipos.includes(equipo.id)
        );
        const cantidadDisponible = equiposDisponibles.length;

        if (cantidadDisponible > 0) {
            const option = document.createElement('option');
            option.value = lote.id;
            option.textContent = 'Lote #' + lote.id + ' - Disponibles: ' + cantidadDisponible;
            option.setAttribute('data-max', cantidadDisponible);
            option.dataset.equipos = JSON.stringify(equiposDisponibles);
            loteSelect.appendChild(option);
            if (cantidadDisponible > maxQuantity) maxQuantity = cantidadDisponible;
        }
    });

    document.getElementById('modalProductTitle').textContent = title;
    // Ajustar cantidad máxima según el primer lote disponible
    if (loteSelect.options.length > 0) {
        document.getElementById('quantityInput').max = loteSelect.options[0].getAttribute('data-max');
        document.getElementById('maxQuantity').textContent = loteSelect.options[0].getAttribute('data-max');
    } else {
        document.getElementById('quantityInput').max = 0;
        document.getElementById('maxQuantity').textContent = 0;
    }

    // Actualizar equipos por serial al cambiar lote
    function actualizarEquiposCheckboxes() {
        const selected = loteSelect.options[loteSelect.selectedIndex];
        const equipos = JSON.parse(selected.dataset.equipos || '[]');
        const equipoCheckboxes = document.getElementById('equipoCheckboxes');
        equipoCheckboxes.innerHTML = '';
        let count = 0;
        equipos.forEach(function(equipo) {
            if (!cartEquipos.includes(equipo.id)) { // Excluir los del carrito
                count++;
                const div = document.createElement('div');
                div.className = 'form-check';
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.className = 'form-check-input';
                input.name = 'equipo_id[]';
                input.value = equipo.id;
                input.id = 'equipoCheck_' + equipo.id;
                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.htmlFor = input.id;
                label.textContent = equipo.numero_serie + ' - ' + (equipo.descripcion || '');
                div.appendChild(input);
                div.appendChild(label);
                equipoCheckboxes.appendChild(div);
            }
        });
        // Si no hay equipos disponibles, mostrar mensaje
        if (count === 0) {
            equipoCheckboxes.innerHTML = '<span class="text-muted">No hay equipos disponibles en este lote.</span>';
        }
    }
    loteSelect.addEventListener('change', function() {
        const selected = loteSelect.options[loteSelect.selectedIndex];
        const loteMax = selected.getAttribute('data-max');
        document.getElementById('quantityInput').max = loteMax;
        document.getElementById('maxQuantity').textContent = loteMax;
        actualizarEquiposCheckboxes();
    });
    loteSelect.dispatchEvent(new Event('change'));
    // Limpiar mensaje de error al abrir modal
    const errorMsg = document.getElementById('modalSerialErrorMsg');
    if (errorMsg) errorMsg.remove();
});

    // Escaneo robusto: Enter activa búsqueda
    scannerInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            const code = scannerInput.value.trim().toLowerCase();
            if (!code) return;

            // Si no es etiqueta, intentar como serial (AJAX al backend)
            fetch("{{ route('solicitud.addBySerial') }}?serial=" + encodeURIComponent(scannerInput.value.trim()), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                }
            })
            .then(res => res.json())
            .then(data => {
                if (typeof data.cart_count !== 'undefined') {
                    const cartBadge = document.querySelector('.btn-outline-primary .badge');
                    if (cartBadge) {
                        cartBadge.textContent = data.cart_count;
                    }
                }
                if (data.success) {
                    // showScannerMsg(data.message, 'success');
                    // scannerInput.value = '';
                    window.location.href = "{{ route('solicitud.cart') }}";
                } else {
                    showScannerMsg(data.message || 'No se encontró equipo con ese serial o no está disponible.');
                }
            })
            .catch(() => {
                showScannerMsg('Error de conexión al buscar el serial.');
            });
        }
    });
});

function openModal() {
    document.getElementById('qrModal').style.display = 'block';
    if (!window.qrScannerInitialized) {
        window.qrScannerInitialized = true;
        const qrReader = new Html5Qrcode("reader");
        qrReader.start({ facingMode: "environment" }, {
            fps: 10,
            qrbox: 250
        }, qrCodeMessage => {
            document.getElementById('result').innerHTML = `<span class='text-success'>${qrCodeMessage}</span>`;
            document.getElementById('scannerInput').value = qrCodeMessage;
            qrReader.stop();
            setTimeout(() => closeModal(), 800);
        }, errorMessage => {
            // Opcional: mostrar errores
        });
        window.qrReader = qrReader;
    } else {
        window.qrReader?.resume();
    }
}
function closeModal() {
    document.getElementById('qrModal').style.display = 'none';
    window.qrReader?.stop();
    document.getElementById('result').innerHTML = '<em>Esperando escaneo...</em>';
}

</script>

@endpush
