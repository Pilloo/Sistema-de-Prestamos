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

      const datatableSearchInput = document.querySelector(".datatable-input");

      if (datatableSearchInput) {
        datatableSearchInput.value = barcodeInput.value;

        datatableSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
      }

      closeBarcodeModal();
    }
  });
});
