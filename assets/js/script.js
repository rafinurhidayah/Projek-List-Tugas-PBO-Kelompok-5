document.addEventListener("DOMContentLoaded", function () {
  const modal = document.getElementById("modalOverlay");
  const btnBuka = document.getElementById("btnBukaModal");
  const btnTutup = document.getElementById("btnTutupModal");
  const btnBatal = document.getElementById("btnBatalModal");

  // Jika tombol buka modal ditemukan di halaman, jalankan fungsi ini
  if (btnBuka) {
    btnBuka.addEventListener("click", () => {
      modal.style.display = "flex";
    });
    btnTutup.addEventListener("click", () => {
      modal.style.display = "none";
    });
    btnBatal.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }
});
