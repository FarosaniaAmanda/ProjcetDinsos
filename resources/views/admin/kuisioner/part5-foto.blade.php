@extends('admin.kuisioner.layout')
@section('content')
<style>
    .part5-foto-wrapper {
        width: 100%;
    }

    .part5-foto-info {
        background: #f5f7ff;
        border: 1px solid #dfe3f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .part5-foto-title {
        font-size: 15px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 5px;
    }

    .part5-foto-description {
        font-size: 13px;
        color: #666;
        margin: 0;
        line-height: 1.5;
    }

    .part5-foto-card {
        background: #fff;
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 14px;
    }

    .part5-foto-question {
        display: flex;
        gap: 8px;
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
    }

    .part5-foto-number {
        min-width: 25px;
        font-size: 11px;
        font-weight: 700;
        color: #252A86;
    }

    .part5-foto-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
    }

    .part5-foto-input {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #d8d8d8;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
    }

    .part5-foto-input:focus {
        outline: none;
        border-color: #252A86;
        box-shadow: 0 0 0 2px rgba(37, 42, 134, .08);
    }

    .part5-foto-camera-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 10px;
        padding: 10px 15px;
        background: #252A86;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .part5-foto-camera-btn:hover {
        background: #1d216d;
        color: #fff;
    }

    .part5-foto-preview {
        margin-top: 12px;
    }

    .part5-foto-preview-box {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 12px;
    }

    .part5-foto-preview img,
    .part5-foto-preview-img {
        display: block;
        width: 180px;
        height: 120px;
        object-fit: cover;
        border-radius: 7px;
        border: 1px solid #ddd;
    }

    .part5-foto-existing {
        font-size: 11px;
        color: #087443;
        margin-top: 6px;
    }

    .part5-foto-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .part5-foto-action-btn {
        border: 1px solid #dfe3f5;
        border-radius: 6px;
        background: #f5f7ff;
        color: #252A86;
        padding: 7px 10px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .part5-foto-action-btn:hover {
        background: #ebefff;
    }

    .part5-foto-delete-btn {
        border-color: #f5c2c7;
        background: #fff3f3;
        color: #b42318;
    }

    .part5-foto-delete-btn:hover {
        background: #ffe9e9;
    }

    .part5-foto-error {
        margin-top: 6px;
        font-size: 11px;
        color: #dc3545;
    }

    .part5-foto-note {
        background: #fff8e8;
        border: 1px solid #f1dfad;
        border-radius: 7px;
        padding: 9px 11px;
        font-size: 11px;
        color: #8a6200;
        margin-top: 15px;
        line-height: 1.5;
    }

    .part5-foto-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-top: 20px;
        padding-bottom: 20px;
    }

    .part5-foto-back,
    .part5-foto-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }

    .part5-foto-back {
        background: #eeeeee;
        color: #444;
    }

    .part5-foto-back:hover {
        background: #e2e2e2;
        color: #333;
    }

    .part5-foto-submit {
        background: #252A86;
        color: #fff;
    }

    .part5-foto-submit:hover {
        background: #1d216d;
    }

    /* MODAL KAMERA */
    .kamera-overlay {
        position: fixed;
        inset: 0;
        z-index: 999999;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 14px;
        padding: 16px;
        background: rgba(0, 0, 0, .94);
    }

    .kamera-overlay.active {
        display: flex;
    }

    .foto-viewer-overlay {
        position: fixed;
        inset: 0;
        display: none;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.85);
        z-index: 999999;
        padding: 20px;
    }

    .foto-viewer-overlay.active {
        display: flex;
    }

    .foto-viewer-content {
        position: relative;
        max-width: 90vw;
        max-height: 85vh;
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    .foto-viewer-close {
        position: absolute;
        right: 12px;
        top: 12px;
        border: none;
        background: #252A86;
        color: #fff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        font-size: 16px;
        font-weight: 700;
    }

    .foto-viewer-title {
        font-size: 14px;
        font-weight: 700;
        color: #252A86;
        margin-bottom: 12px;
        padding-right: 36px;
    }

    .foto-viewer-content img {
        display: block;
        max-width: 80vw;
        max-height: 70vh;
        border-radius: 8px;
        object-fit: contain;
        background: #f6f7ff;
    }

    .kamera-title {
        color: #fff;
        font-size: 17px;
        font-weight: 700;
        text-align: center;
    }

    .kamera-video {
        display: block;
        width: 100%;
        max-width: 600px;
        max-height: 65vh;
        background: #111;
        border-radius: 10px;
        object-fit: contain;
    }

    .kamera-controls {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .kamera-btn {
        border: none;
        border-radius: 8px;
        padding: 12px 18px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .kamera-ambil {
        color: #fff;
        background: #198754;
    }

    .kamera-tutup {
        color: #fff;
        background: #dc3545;
    }

    .kamera-switch {
        color: #252A86;
        background: #fff;
    }

    .kamera-status {
        color: #fff;
        font-size: 12px;
        text-align: center;
        max-width: 500px;
    }

    @media (max-width: 768px) {
        .part5-foto-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .part5-foto-back,
        .part5-foto-submit {
            width: 100%;
        }

        .part5-foto-camera-btn {
            width: 100%;
        }

        .kamera-video {
            max-height: 60vh;
        }
    }
</style>

<div class="part5-foto-wrapper">

    {{-- INFORMASI --}}
    <div class="part5-foto-info">
        <div class="part5-foto-title">
            Part 5 — Foto Rumah
        </div>

        <p class="part5-foto-description">
            Silakan ambil foto menggunakan kamera atau upload foto
            kondisi rumah sesuai dengan bagian yang diminta.
        </p>
    </div>

    <form
        id="form-foto-rumah"
        action="{{ route('kuisioner.part5.foto.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        {{-- FOTO TAMPAK DEPAN --}}
        <div class="part5-foto-card" data-foto="tampak_depan">

            <div class="part5-foto-question">
                <span class="part5-foto-number">69.</span>
                <span>Foto Rumah — Tampak Depan</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Tampak Depan
            </label>

            <input
                type="file"
                name="tampak_depan"
                class="part5-foto-input"
                accept="image/jpeg,image/png,image/jpg"
                required
            >

            <button
                type="button"
                class="part5-foto-camera-btn"
                data-camera="tampak_depan"
            >
                Buka Kamera
            </button>

            <div class="part5-foto-preview" data-preview="tampak_depan"></div>

            @if (isset($fotoRumah['tampak_depan']))
                <div class="part5-foto-existing">
                    
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['tampak_depan']->path_file) }}"
                        alt="Foto Tampak Depan"
                    >
                </div>
            @endif

            @error('tampak_depan')
                <div class="part5-foto-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- FOTO RUANG TAMU --}}
        <div class="part5-foto-card" data-foto="ruang_tamu">

            <div class="part5-foto-question">
                <span class="part5-foto-number">70.</span>
                <span>Foto Rumah — Ruang Tamu</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Ruang Tamu
            </label>

            <input
                type="file"
                name="ruang_tamu"
                class="part5-foto-input"
                accept="image/jpeg,image/png,image/jpg"
                required
            >

            <button
                type="button"
                class="part5-foto-camera-btn"
                data-camera="ruang_tamu"
            >
                 Buka Kamera
            </button>

            <div class="part5-foto-preview" data-preview="ruang_tamu"></div>

            @if (isset($fotoRumah['ruang_tamu']))
                <div class="part5-foto-existing">
                
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['ruang_tamu']->path_file) }}"
                        alt="Foto Ruang Tamu"
                    >
                </div>
            @endif

            @error('ruang_tamu')
                <div class="part5-foto-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- FOTO KAMAR MANDI --}}
        <div class="part5-foto-card" data-foto="kamar_mandi">

            <div class="part5-foto-question">
                <span class="part5-foto-number">71.</span>
                <span>Foto Rumah — Kamar Mandi</span>
            </div>

            <label class="part5-foto-label">
                Upload Foto Kamar Mandi
            </label>

            <input
                type="file"
                name="kamar_mandi"
                class="part5-foto-input"
                accept="image/jpeg,image/png,image/jpg"
                required
            >

            <button
                type="button"
                class="part5-foto-camera-btn"
                data-camera="kamar_mandi"
            >
                Buka Kamera
            </button>

            <div class="part5-foto-preview" data-preview="kamar_mandi"></div>

            @if (isset($fotoRumah['kamar_mandi']))
                <div class="part5-foto-existing">
                    
                </div>

                <div class="part5-foto-preview">
                    <img
                        src="{{ asset('storage/' . $fotoRumah['kamar_mandi']->path_file) }}"
                        alt="Foto Kamar Mandi"
                    >
                </div>
            @endif

            @error('kamar_mandi')
                <div class="part5-foto-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- CATATAN --}}
        <div class="part5-foto-note">
            Format foto yang diperbolehkan: JPG, JPEG, atau PNG.
            Ukuran maksimal setiap foto adalah 5 MB.
        </div>

        {{-- FOOTER --}}
        <div class="part5-foto-footer">

            <a
                href="{{ route('kuisioner.part5') }}"
                class="part5-foto-back"
            >
                ← Kembali ke Daftar Anggota
            </a>

            <button
                type="submit"
                class="part5-foto-submit"
            >
                Simpan & Selesaikan Kuisioner
            </button>

        </div>

    </form>
</div>

{{-- MODAL KAMERA --}}
<div
    id="kamera-overlay"
    class="kamera-overlay"
    role="dialog"
    aria-modal="true"
    aria-label="Kamera foto rumah"
>
    <div class="kamera-title" id="kamera-title">
        Ambil Foto Rumah
    </div>

    <video
        id="kamera-video"
        class="kamera-video"
        autoplay
        playsinline
        muted
    ></video>

    <canvas id="kamera-canvas" style="display:none;"></canvas>

    <div class="kamera-status" id="kamera-status">
        Arahkan kamera ke bagian rumah yang ingin difoto.
    </div>

    <div class="kamera-controls">

        <button
            type="button"
            id="kamera-ambil"
            class="kamera-btn kamera-ambil"
        >
            📷 Ambil Foto
        </button>

        <button
            type="button"
            id="kamera-switch"
            class="kamera-btn kamera-switch"
        >
            🔄 Ganti Kamera
        </button>

        <button
            type="button"
            id="kamera-tutup"
            class="kamera-btn kamera-tutup"
        >
            Tutup
        </button>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('kamera-overlay');
    const video = document.getElementById('kamera-video');
    const canvas = document.getElementById('kamera-canvas');
    const title = document.getElementById('kamera-title');
    const status = document.getElementById('kamera-status');
    const captureButton = document.getElementById('kamera-ambil');
    const switchButton = document.getElementById('kamera-switch');
    const closeButton = document.getElementById('kamera-tutup');

    let activeStream = null;
    let activeInput = null;
    let activePreview = null;
    let cameraFacing = 'environment';
    let cameraBusy = false;

    function stopCamera() {
        if (activeStream) {
            activeStream.getTracks().forEach(function (track) {
                track.stop();
            });
            activeStream = null;
        }

        video.srcObject = null;
    }

    function closeCamera() {
        stopCamera();
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        activeInput = null;
        activePreview = null;
        cameraBusy = false;
    }

    async function startCamera(facingMode) {
        if (!navigator.mediaDevices ||
            !navigator.mediaDevices.getUserMedia) {
            status.textContent =
                'Browser tidak mendukung kamera. Gunakan HTTPS atau localhost.';
            alert(
                'Kamera tidak tersedia. Buka website melalui HTTPS atau localhost, lalu coba lagi.'
            );
            return;
        }

        stopCamera();

        status.textContent = 'Menghubungkan kamera...';
        captureButton.disabled = true;

        try {
            activeStream = await navigator.mediaDevices.getUserMedia({
                audio: false,
                video: {
                    facingMode: { ideal: facingMode },
                    width: { ideal: 1280 },
                    height: { ideal: 720 }
                }
            });

            video.srcObject = activeStream;
            cameraFacing = facingMode;

            await video.play();

            status.textContent =
                'Arahkan kamera ke bagian rumah, lalu tekan Ambil Foto.';

            captureButton.disabled = false;

        } catch (error) {
            stopCamera();

            let message =
                'Kamera gagal dibuka. Pastikan izin kamera telah diberikan.';

            if (error.name === 'NotAllowedError') {
                message =
                    'Akses kamera ditolak. Izinkan penggunaan kamera melalui pengaturan browser.';
            } else if (error.name === 'NotFoundError') {
                message =
                    'Kamera tidak ditemukan pada perangkat ini.';
            } else if (error.name === 'NotReadableError') {
                message =
                    'Kamera sedang digunakan aplikasi lain. Tutup aplikasi tersebut lalu coba lagi.';
            } else if (error.name === 'OverconstrainedError') {
                message =
                    'Kamera tidak mendukung pengaturan yang diminta. Coba ganti kamera.';
            }

            status.textContent = message;
            alert(message);
            captureButton.disabled = true;
        }
    }

    document.querySelectorAll('[data-camera]').forEach(function (button) {
        button.addEventListener('click', async function () {
            const fieldName = button.dataset.camera;
            const card = button.closest('.part5-foto-card');

            activeInput = card.querySelector(
                'input[name="' + fieldName + '"]'
            );

            activePreview = card.querySelector(
                '[data-preview="' + fieldName + '"]'
            );

            const labels = {
                tampak_depan: 'Foto Rumah — Tampak Depan',
                ruang_tamu: 'Foto Rumah — Ruang Tamu',
                kamar_mandi: 'Foto Rumah — Kamar Mandi'
            };

            title.textContent = labels[fieldName] || 'Ambil Foto Rumah';
            cameraFacing = 'environment';

            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';

            await startCamera(cameraFacing);
        });
    });

    captureButton.addEventListener('click', function () {
        if (cameraBusy || !activeStream ||
            !video.videoWidth || !video.videoHeight) {
            return;
        }

        cameraBusy = true;
        captureButton.disabled = true;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');

        if (!context) {
            cameraBusy = false;
            captureButton.disabled = false;
            alert('Gagal memproses foto. Silakan coba lagi.');
            return;
        }

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        canvas.toBlob(function (blob) {
            if (!blob) {
                cameraBusy = false;
                captureButton.disabled = false;
                alert('Gagal mengambil foto. Silakan coba lagi.');
                return;
            }

            const fieldName = activeInput.name;

            const file = new File(
                [blob],
                fieldName + '-' + Date.now() + '.jpg',
                { type: 'image/jpeg', lastModified: Date.now() }
            );

            const transfer = new DataTransfer();
            transfer.items.add(file);
            activeInput.files = transfer.files;

            activePreview.innerHTML = '';

            const previewImage = document.createElement('img');
            previewImage.className = 'part5-foto-preview-img';
            previewImage.alt = 'Preview ' + fieldName;
            previewImage.src = URL.createObjectURL(file);

            activePreview.appendChild(previewImage);

            const successText = document.createElement('div');
            successText.className = 'part5-foto-existing';
            successText.textContent = '';
            activePreview.appendChild(successText);

            closeCamera();
        }, 'image/jpeg', 0.9);
    });

    switchButton.addEventListener('click', async function () {
        const nextFacing =
            cameraFacing === 'environment' ? 'user' : 'environment';

        await startCamera(nextFacing);
    });

    closeButton.addEventListener('click', closeCamera);

    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) {
            closeCamera();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && overlay.classList.contains('active')) {
            closeCamera();
        }
    });

    /*
     * Preview jika pengguna memilih gambar dari galeri/file.
     */
    document.querySelectorAll('.part5-foto-input').forEach(function (input) {
        input.addEventListener('change', function () {
            if (!input.files || !input.files[0]) {
                return;
            }

            const file = input.files[0];

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar.');
                input.value = '';
                return;
            }

            const card = input.closest('.part5-foto-card');
            const preview = card.querySelector(
                '[data-preview="' + input.name + '"]'
            );

            preview.innerHTML = '';

            const image = document.createElement('img');
            image.className = 'part5-foto-preview-img';
            image.alt = 'Preview ' + input.name;
            image.src = URL.createObjectURL(file);

            preview.appendChild(image);

            const successText = document.createElement('div');
            successText.className = 'part5-foto-existing';
            successText.textContent = '';

            preview.appendChild(successText);
        });
    });

    /*
     * Hentikan kamera jika halaman ditinggalkan.
     */
    window.addEventListener('pagehide', stopCamera);
});
</script>

@endsection
