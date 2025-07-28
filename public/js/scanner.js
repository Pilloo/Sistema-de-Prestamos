let html5QrCode;
let scannerRunning = false;

const modalEl = document.getElementById('qrModal');

modalEl.addEventListener('shown.bs.modal', function () {
  setTimeout(startScanner, 500); // Espera 500ms antes de iniciar
});


modalEl.addEventListener('hidden.bs.modal', function () {
  stopScanner();
});

function startScanner() {
  if (scannerRunning) return;

  html5QrCode = new Html5Qrcode("reader");
  const config = { fps: 10, qrbox: { width: 250, height: 250 } };

  html5QrCode.start(
    { facingMode: "environment" },
    config,
    qrCodeMessage => {
      document.getElementById("result").classList.remove("d-none");
      document.getElementById("result").innerText = "Resultado: " + qrCodeMessage;

      // 👉 Llenar automáticamente el campo
      document.getElementById("contenido_etiqueta").value = qrCodeMessage;

      // Cerrar el modal
      const modal = bootstrap.Modal.getInstance(modalEl);
      modal.hide();
    },
    error => {
      // silencioso
    }
  ).then(() => {
    scannerRunning = true;
  }).catch(err => {
    console.error("Error iniciando escáner:", err);
  });
}

function stopScanner() {
  if (!scannerRunning || !html5QrCode) return;

  html5QrCode.stop().then(() => {
    html5QrCode.clear();
    scannerRunning = false;
  }).catch(err => {
    console.error("Error deteniendo escáner:", err);
  });
}
