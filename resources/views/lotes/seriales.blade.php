@extends('template')

@section('content')
<h3>Agregar seriales para Lote #{{ $loteEquipo->id }} (faltan {{ $faltantes }})</h3>

@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<form method="POST" action="{{ route('lotes.seriales.store', $loteEquipo->id) }}">
    @csrf

    <div id="serialsContainer">
        @for($i = 0; $i < $faltantes; $i++)
            <div class="form-group mb-3">
                <label>Serial {{ $i+1 }}</label>
                <input name="seriales[{{ $i }}][numero]" class="form-control" required maxlength="255" />

                <label for="estado_equipo_id_{{ $i }}" class="form-label mt-2">Estado</label>
                <select name="seriales[{{ $i }}][estado_equipo_id]" id="estado_equipo_id_{{ $i }}" class="form-select selectpicker show-tick" data-size="4" data-live-search="true">
                    @foreach ($estado_equipos as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
            </div>
        @endfor
    </div>

    <button type="submit" class="btn btn-primary">Guardar seriales</button>
</form>
@endsection
