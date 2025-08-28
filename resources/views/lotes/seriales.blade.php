@extends('template')

@push('css')
<link href="{{ asset('css/lotes.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeIn">
        <div class="card-body p-4">
            <h3 class="fw-bold text-primary mb-3">
                Agregar seriales para Lote #{{ $loteEquipo->id }}
                <small class="text-muted d-block fs-6">
                    Faltan <span id="faltanCounter">{{ $faltantes }}</span>
                </small>
            </h3>

            @if(session('error'))
                <div class="alert alert-danger shadow-sm rounded-3">{{ session('error') }}</div>
            @endif

            <!-- Lista dinámica -->
            <ul id="listaSeriales" class="list-group list-group-flush mb-4 border rounded-3"></ul>

            <!-- Acciones -->
            <div class="d-flex flex-wrap gap-3">
                <button type="button" 
                        class="btn btn-gradient btn-lg flex-grow-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#serialModal" 
                        id="btnAbrirModal">
                    <i class="bi bi-plus-circle me-1"></i> Agregar Serial
                </button>

                <form id="formSeriales" method="POST" 
                      action="{{ route('lotes.seriales.store', $loteEquipo->id) }}" 
                      class="flex-grow-1">
                    @csrf
                    <div id="inputsHidden"></div>
                    <!-- 🔥 le agregamos id al botón -->
                    <button type="submit" id="btnGuardar" class="btn btn-success btn-lg shadow-sm w-100" disabled>
                        <i class="bi bi-save me-1"></i> Guardar seriales
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="serialModal" tabindex="-1" aria-labelledby="serialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="serialModalLabel">Agregar Serial</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Número de Serial</label>
                    <input id="serialInput" type="text" 
                           class="form-control rounded-3 shadow-sm" 
                           maxlength="255" 
                           placeholder="Ingrese el serial..." required>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Estado</label>
                    <select id="estadoInput" class="form-select rounded-3 shadow-sm">
                        @foreach ($estado_equipos as $item)
                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer bg-light rounded-bottom-4">
                <button type="button" class="btn btn-outline-secondary rounded-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-gradient rounded-3" id="btnAgregarSerial">
                    <i class="bi bi-check-circle me-1"></i> Agregar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script>
let index = 0;
const maxSeriales = {{ $faltantes }};
const btnAgregarModal = document.getElementById('btnAgregarSerial');
const btnAbrirModal = document.getElementById('btnAbrirModal');
const listaSeriales = document.getElementById('listaSeriales');
const inputsHidden = document.getElementById('inputsHidden');
const faltanCounter = document.getElementById('faltanCounter');
const btnGuardar = document.getElementById('btnGuardar'); // 🔥 referencia al botón de guardar

function actualizarContador() {
    const restantes = maxSeriales - listaSeriales.children.length;
    faltanCounter.textContent = restantes;

    // Deshabilitar "Agregar" si ya llegó al máximo
    btnAbrirModal.disabled = (restantes <= 0);

    // 🔥 Habilitar "Guardar" solo si ya se completaron todos los seriales
    btnGuardar.disabled = (restantes !== 0);
}

btnAgregarModal.addEventListener('click', function() {
    const serial = document.getElementById('serialInput').value.trim();
    const estado = document.getElementById('estadoInput').value;
    const estadoTexto = document.getElementById('estadoInput').selectedOptions[0].text;

    if (serial === '') return;

    // Item visual
    let li = document.createElement('li');
    li.className = "list-group-item d-flex justify-content-between align-items-center";
    li.innerHTML = `
        <span><strong>${serial}</strong> 
        <span class="badge bg-info ms-2">${estadoTexto}</span></span>
        <button type="button" class="btn btn-sm btn-outline-danger btnEliminar rounded-circle">
            <i class="bi bi-x-lg"></i>
        </button>
    `;
    listaSeriales.appendChild(li);

    // Inputs ocultos
    let wrapper = document.createElement('div');
    wrapper.classList.add('serial-wrapper');
    wrapper.innerHTML = `
        <input type="hidden" name="seriales[${index}][numero]" value="${serial}">
        <input type="hidden" name="seriales[${index}][estado_equipo_id]" value="${estado}">
    `;
    inputsHidden.appendChild(wrapper);
    index++;

    // Reset form modal
    document.getElementById('serialInput').value = '';
    document.getElementById('estadoInput').selectedIndex = 0;
    bootstrap.Modal.getInstance(document.getElementById('serialModal')).hide();

    // Eliminar
    li.querySelector('.btnEliminar').addEventListener('click', function() {
        li.remove();
        wrapper.remove();
        actualizarContador();
    });

    actualizarContador();
});

actualizarContador();
</script>
@endpush
