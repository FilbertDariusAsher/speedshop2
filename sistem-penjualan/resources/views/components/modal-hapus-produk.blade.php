<div class="modal fade" id="modalHapusProduk" tabindex="-1" aria-labelledby="modalHapusProdukLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-body p-4">
        <div class="d-flex align-items-start gap-3">
          <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; flex-shrink: 0;">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
          </div>
          <div class="flex-grow-1">
            <h5 class="modal-title fw-bold mb-2" id="modalHapusProdukLabel">Hapus Produk</h5>
            <p class="text-muted mb-3">Tindakan ini tidak bisa dibatalkan. Yakin ingin menghapus produk ini?</p>
            <div class="alert alert-light border border-danger-subtle rounded-3 py-2 px-3 mb-0 small text-danger">
              <i class="bi bi-info-circle me-2"></i>Produk yang dihapus akan hilang dari daftar produk.
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 px-4 pb-4 pt-0">
        <button type="button" class="btn btn-light border rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger rounded-pill px-4" id="btnKonfirmasiHapus">Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script>
let formHapusTerpilih = null;

document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.btn-hapus-produk').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      formHapusTerpilih = btn.closest('form');
      const modal = new bootstrap.Modal(document.getElementById('modalHapusProduk'));
      modal.show();
    });
  });

  document.getElementById('btnKonfirmasiHapus').addEventListener('click', function() {
    if (formHapusTerpilih) {
      formHapusTerpilih.submit();
    }
  });
});
</script>
