// Modal konfirmasi hapus produk
// Modal ini akan di-inject ke halaman produk.blade.php

<div class="modal fade" id="modalHapusProduk" tabindex="-1" aria-labelledby="modalHapusProdukLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="modalHapusProdukLabel">Konfirmasi Hapus Produk</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p class="mb-0">Yakin ingin menghapus produk ini?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger" id="btnKonfirmasiHapus">Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
let formHapusTerpilih = null;

document.addEventListener('DOMContentLoaded', function() {
  // Tangkap semua tombol hapus
  document.querySelectorAll('.btn-hapus-produk').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      formHapusTerpilih = btn.closest('form');
      const modal = new bootstrap.Modal(document.getElementById('modalHapusProduk'));
      modal.show();
    });
  });

  // Tombol konfirmasi di modal
  document.getElementById('btnKonfirmasiHapus').addEventListener('click', function() {
    if (formHapusTerpilih) {
      formHapusTerpilih.submit();
    }
  });
});
</script>
