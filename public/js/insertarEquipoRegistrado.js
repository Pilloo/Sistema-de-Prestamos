document.addEventListener('DOMContentLoaded', function () {
    const selectEquipo = document.getElementById('equipo_id');
    const modelo = document.getElementById('modelo');
    const numeroSerie = document.getElementById('numero_serie');
    const contenidoEtiqueta = document.getElementById('contenido_etiqueta');
    const detalle = document.getElementById('detalle');
    //const cantidadTotal = document.getElementById('cantidad_total');
    const marcaSelect = document.getElementById('marca_id');
    const estadoSelect = document.getElementById('estado_equipo_id');
    const rutaImagen = document.getElementById('img_path');
    const cantidadLabel = document.getElementById('cantidad_label');
    const preview = document.getElementById('preview');
    const checkboxes = document.querySelectorAll('input[name="categorias[]"]');

    function marcarCategorias(equipo, cargar) {

        if (cargar) {
            checkboxes.forEach(cb => cb.checked = false);
            equipo.categorias.forEach(id => {
                const checkbox = document.querySelector(`input[name="categorias[]"][value="${id}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }else{
            checkboxes.forEach(cb => cb.checked = false);
        }
    }

    function bloquearCampos(bloquear = true) {
        modelo.readOnly = bloquear;
        numeroSerie.readOnly = bloquear;
        contenidoEtiqueta.readOnly = bloquear;
        detalle.readOnly = bloquear;
        marcaSelect.disabled = bloquear;
        estadoSelect.disabled = bloquear;
        rutaImagen.disabled = bloquear;
        checkboxes.forEach(cb => {
            cb.disabled = bloquear;
        });
    }

    function limpiarCampos() {
        modelo.value = '';
        numeroSerie.value = '';
        contenidoEtiqueta.value = '';
        detalle.value = '';
        cantidadLabel.innerText = 'Cantidad:';
        //cantidadTotal.value = '';
        marcaSelect.value = '';
        estadoSelect.value = '';
        preview.src = '';
        preview.style.display = 'none';
    }

    selectEquipo.addEventListener('change', function () {
        const equipoId = this.value;
        
        if (equipoId) {
            const equipo = equiposData.find(e => e.id == equipoId);

            if (equipo) {
                modelo.value = equipo.modelo || '';
                numeroSerie.value = equipo.numero_serie || '';
                contenidoEtiqueta.value = equipo.contenido_etiqueta || '';
                detalle.value = equipo.detalle || '';
                cantidadLabel.innerText = 'Cantidad a agregar:';
                //cantidadTotal.value = equipo.cantidad_total || '';
                marcaSelect.value = equipo.marca_id || '';
                estadoSelect.value = equipo.estado_equipo_id || '';
                bloquearCampos(true);
                marcarCategorias(equipo, true);

                if (equipo.img_path) {
                    preview.src = `/img/equipos/${equipo.img_path}`;
                    preview.style.display = 'block';
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            }
        } else {
            limpiarCampos();
            bloquearCampos(false);
            marcarCategorias(null, false);
            preview.src = '';
            preview.style.display = 'none';
        }
    });
});