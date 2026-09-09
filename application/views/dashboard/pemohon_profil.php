<?php $this->load->view('layout/header', ['title' => $title]); ?>
<?php $this->load->view('layout/nav_pemohon'); ?>

<style>
    body { background-color: #f0fdf4; }
    .pemohon-container { margin-top: 100px; margin-bottom: 60px; min-height: 70vh; }
    .card-profil { border: none; border-radius: 12px; box-shadow: 0 6px 20px rgba(0,0,0,0.04); overflow: hidden; }
    .card-profil .card-header { background: linear-gradient(135deg, var(--nav-primary) 0%, var(--nav-secondary) 100%); color: white; padding: 20px 25px; border-bottom: none; font-weight: 600; font-size: 1.2rem; }
    .card-profil .card-body { padding: 30px; background-color: white; }
    .profile-pic-preview { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
    .btn-save { background-color: var(--nav-primary); color: white; padding: 10px 25px; border-radius: 8px; font-weight: 600; border: none; transition: all 0.3s; }
    .btn-save:hover { background-color: var(--nav-secondary); color: white; transform: translateY(-2px); }
</style>

<div class="container pemohon-container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= $this->session->flashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card card-profil">
                <div class="card-header text-center">
                    <i class="fas fa-id-card me-2"></i> Profil Detail Pemohon
                </div>
                <div class="card-body">
                    <?= form_open_multipart('pemohon-dashboard/update_profil') ?>
                        <div class="text-center mb-4">
                            <?php 
                                if (!empty($user['foto_profil'])) {
                                    if (strpos($user['foto_profil'], 'data:image') === 0 || strpos($user['foto_profil'], 'http') === 0) {
                                        $foto = $user['foto_profil'];
                                    } elseif (file_exists(FCPATH . 'uploads/profil/' . $user['foto_profil'])) {
                                        $foto = base_url('uploads/profil/' . $user['foto_profil']);
                                    } else {
                                        $foto = 'https://ui-avatars.com/api/?name=' . urlencode($pemohon ? $pemohon['nama_pemilik'] : $user['username']) . '&background=065f46&color=fff&size=150';
                                    }
                                } else {
                                    $foto = 'https://ui-avatars.com/api/?name=' . urlencode($pemohon ? $pemohon['nama_pemilik'] : $user['username']) . '&background=065f46&color=fff&size=150';
                                }
                            ?>
                            <img src="<?= $foto ?>" alt="Foto Profil" class="profile-pic-preview" id="preview-image">
                            <div class="mt-2">
                                <label for="foto_profil" class="form-label d-block text-muted" style="font-size: 0.9rem;">Unggah Foto Baru (Maks 2MB, JPG/PNG)</label>
                                <input type="file" class="form-control form-control-sm mx-auto" id="foto_profil" name="foto_profil" style="max-width: 250px;" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Pemilik / Penanggung Jawab <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_pemilik" value="<?= isset($pemohon['nama_pemilik']) ? htmlspecialchars($pemohon['nama_pemilik']) : '' ?>" required placeholder="Masukkan nama lengkap">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Usaha <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alamat_usaha" rows="3" required placeholder="Masukkan alamat lengkap usaha"><?= isset($pemohon['alamat_usaha']) ? htmlspecialchars($pemohon['alamat_usaha']) : '' ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Jenis Usaha <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="jenis_usaha" value="<?= isset($pemohon['jenis_usaha']) ? htmlspecialchars($pemohon['jenis_usaha']) : '' ?>" required placeholder="Contoh: Toko Emas, SPBU">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Kontak (No. HP/WA) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="kontak_pemohon" value="<?= isset($pemohon['kontak_pemohon']) ? htmlspecialchars($pemohon['kontak_pemohon']) : '' ?>" required placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ID Registrasi Pemohon</label>
                            <input type="text" class="form-control bg-light fw-bold text-primary" value="<?= isset($pemohon['id_pemohon']) ? htmlspecialchars($pemohon['id_pemohon']) : '' ?>" readonly disabled>
                            <small class="text-muted">ID ini digunakan sebagai identitas resmi pendaftaran Anda.</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Email Login</label>
                            <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>
                            <small class="text-muted">Email digunakan untuk masuk ke sistem dan tidak dapat diubah di sini.</small>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-save"><i class="fas fa-save me-2"></i> Simpan Perubahan</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('preview-image');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<?php $this->load->view('layout/footer'); ?>
