let html5QrCode;
    let scannerRunning = false;

    function openModal() {
      document.getElementById('qrModal').style.display = 'flex';
      startScanner();
    }

    function closeModal() {
      document.getElementById('qrModal').style.display = 'none';
      stopScanner();
    }

    function startScanner() {
      if (scannerRunning) return;

      html5QrCode = new Html5Qrcode("reader");

      const config = { fps: 10, qrbox: { width: 250, height: 250 } };

      html5QrCode.start(
        { facingMode: "environment" },
        config,
        qrCodeMessage => {
        document.getElementById("result").innerHTML = "Resultado: " + qrCodeMessage;
        document.getElementById("contenido_etiqueta").value = qrCodeMessage;
        stopScanner();
      },
        errorMessage => {
          // silenciado para no molestar al usuario
        }
      ).then(() => {
        scannerRunning = true;
      }).catch(err => {
        console.error("No se pudo iniciar:", err);
      });
    }

    function stopScanner() {
      if (!scannerRunning || !html5QrCode) return;

      html5QrCode.stop().then(() => {
        html5QrCode.clear();
        scannerRunning = false;
      }).catch(err => {
        console.error("No se pudo detener:", err);
      });
    }