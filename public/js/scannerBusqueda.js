function openBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'flex';

  const input = document.getElementById("barcodeInput") || document.querySelector(".datatable-input");
  if (input) {
    input.value = "";
    input.focus();
  }
}

function closeBarcodeModal() {
  document.getElementById('barcodeModal').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
  const barcodeInput = document.getElementById("barcodeInput");

  if (!barcodeInput) return;

  barcodeInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();

      // Este es el input que aparece en el buscador de la tabla
      const datatableSearchInput = document.querySelector(".datatable-input");

      if (datatableSearchInput) {
        datatableSearchInput.value = barcodeInput.value;

        // ⚠️ Dispara el evento `input`, para que la tabla actualice los resultados
        datatableSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
      }

      closeBarcodeModal();
    }
  });
});
