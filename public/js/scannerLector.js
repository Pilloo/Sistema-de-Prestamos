function openBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'flex';
  const input = document.getElementById("barcodeInput");
  input.value = "";
  input.focus();
}

function closeBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
  const barcodeInput = document.getElementById("barcodeInput");

  barcodeInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      document.getElementById("contenido_etiqueta").value = barcodeInput.value;
      closeBarcodeModal();
    }
  });
});