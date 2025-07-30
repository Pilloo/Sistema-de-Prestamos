function openBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'flex';
  const input = document.getElementById("barcodeInput");
  input.value = "";
  input.focus();
}

function closeBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'none';
}

// Cuando se escanea el código de barras, asigna el valor y cierra el modal
document.addEventListener('DOMContentLoaded', function () {
  const barcodeInput = document.getElementById("barcodeInput");

  barcodeInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault(); // Evita que se envíe el formulario
      document.getElementById("contenido_etiqueta").value = barcodeInput.value;
      closeBarcodeModal();
    }
  });
});