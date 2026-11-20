
@extends('layouts.main')

@section('title', 'Absensi PKL - PKL Connect')
@section('page-heading', 'Absensi PKL')

@section('content')
<div class="container-fluid px-0">

    @php
        $jadwalLibur = $placement
            ? \App\Models\Schedule::where('placement_id', $placement->id)
                ->whereDate('date', today())
                ->where('status', 'libur')
                ->exists()
            : false;
    @endphp

    <div class="mb-4">
        <h4 class="fw-bold mb-1">Absensi PKL</h4>
        <p class="text-muted mb-0">Catat kehadiran dan kepulanganmu sesuai lokasi PKL.</p>
    </div>

    @if(!$placement)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Kamu tidak memiliki penempatan PKL aktif hari ini.
        </div>

    @elseif($jadwalLibur)
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center py-4">
                <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center p-4 mb-3">
                    <i class="bi bi-calendar-x text-danger" style="font-size: 3rem;"></i>
                </div>

                <h4 class="fw-bold mb-2" style="color:#14213d;">
                    Hari Ini Jadwal Libur
                </h4>

                <p class="text-muted mb-3">
                    Kamu tidak perlu melakukan absensi hari ini.
                    Silakan gunakan waktu libur dengan baik.
                </p>

                <span class="badge rounded-pill px-3 py-2"
                      style="background:#fef2f2;color:#dc2626;">
                    <i class="bi bi-calendar-check me-1"></i>
                    LIBUR PKL
                </span>
            </div>
        </div>

    @elseif($attendance && in_array($attendance->status, ['izin', 'sakit']))
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center">
                <i class="bi bi-calendar-check text-success fs-1"></i>
                <h5 class="fw-bold mt-3">Absensi Sudah Tercatat</h5>
                <p class="text-muted mb-2">Status kehadiran hari ini:</p>
                <span class="badge bg-info text-dark fs-6">
                    {{ ucfirst($attendance->status) }}
                </span>
            </div>
        </div>

    @elseif($attendance && $attendance->check_out_time)
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="text-center">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
                <h5 class="fw-bold mt-3">Absensi Hari Ini Selesai</h5>
                <p class="text-muted mb-1">Absen masuk: {{ $attendance->check_in_time }}</p>
                <p class="text-muted mb-0">Absen pulang: {{ $attendance->check_out_time }}</p>
            </div>
        </div>

    @else
        <div class="row g-4">

            {{-- INFORMASI PENEMPATAN --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-3 p-3 bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-building fs-3"></i>
                            </div>
                            <div>
                                <small class="text-muted">Lokasi PKL</small>
                                <h6 class="fw-bold mb-0">
                                    {{ $placement->location->name ?? 'Lokasi PKL' }}
                                </h6>
                                <small class="text-muted">
                                    {{ $placement->location->address ?? '-' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KAMERA DAN DETEKSI WAJAH --}}
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">

                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="fw-bold mb-0">Foto Kehadiran</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary">
                                {{ $attendance && $attendance->check_in_time ? 'Absen Pulang' : 'Absen Masuk' }}
                            </span>
                        </div>

                        <div class="camera-frame position-relative bg-dark rounded-4 overflow-hidden mb-3">
                            <video id="cameraVideo" autoplay playsinline muted></video>

                            <div id="cameraPlaceholder"
                                 class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-3">
                                <i class="bi bi-camera-video fs-1"></i>
                                <p class="mt-2 mb-0">Kamera belum diaktifkan</p>
                            </div>

                            <img id="photoPreview" alt="Pratinjau foto absensi">
                            <canvas id="cameraCanvas" class="d-none"></canvas>
                        </div>

                        <div id="faceAlert" class="alert alert-secondary py-2 small mb-3" role="status">
                            <i class="bi bi-info-circle me-1"></i>
                            Aktifkan kamera untuk memulai pemeriksaan wajah.
                        </div>

                        <div id="cameraMessage" class="small text-muted mb-3">
                            Gunakan kamera depan dan pastikan wajah terlihat jelas.
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" id="startCamera" class="btn btn-outline-primary">
                                <i class="bi bi-camera-video me-1"></i>
                                Aktifkan Kamera
                            </button>

                            <button type="button" id="takePhoto" class="btn btn-primary" disabled>
                                <i class="bi bi-camera-fill me-1"></i>
                                Ambil Foto
                            </button>
                        </div>

                        <small class="text-muted d-block mt-3">
                            <i class="bi bi-shield-check me-1"></i>
                            Foto hanya diambil melalui kamera langsung. Ukuran maksimal 4 MB.
                        </small>

                        <input type="file"
                               id="photoInput"
                               name="photo"
                               form="attendanceForm"
                               accept="image/jpeg,image/png"
                               class="d-none">

                    </div>
                </div>
            </div>

            {{-- GPS, PETA, DAN FORM --}}
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">Lokasi Kehadiran</h5>

                        <div class="rounded-3 bg-light p-3 mb-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-geo-alt-fill text-primary fs-5"></i>
                                <strong>Status GPS</strong>
                            </div>

                            <div id="locationStatus" class="small text-muted">
                                Sedang mencari lokasi...
                            </div>

                            <button type="button" id="refreshLocation"
                                    class="btn btn-sm btn-outline-primary mt-3">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Perbarui Lokasi
                            </button>
                        </div>

                        <div id="attendanceMap" class="attendance-map mb-3"></div>

                        <div class="mb-3">
                            <small class="text-muted d-block">Tanggal</small>
                            <strong>{{ now()->translatedFormat('d F Y') }}</strong>
                        </div>

                        @if($attendance && $attendance->check_in_time)
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                Absen masuk tercatat pukul
                                <strong>{{ $attendance->check_in_time }}</strong>
                            </div>
                        @endif

                        <form id="attendanceForm"
                              method="POST"
                              action="{{ $attendance && $attendance->check_in_time ? route('student.absen.pulang') : route('student.absen.masuk') }}"
                              enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="latitude" id="latitude">
                            <input type="hidden" name="longitude" id="longitude">

                            <button type="submit"
                                    id="submitAttendance"
                                    class="btn btn-primary btn-lg w-100 rounded-3"
                                    disabled>
                                <i class="bi bi-fingerprint me-2"></i>
                                {{ $attendance && $attendance->check_in_time ? 'Absen Pulang' : 'Absen Masuk' }}
                            </button>

                            <p class="text-muted small text-center mt-3 mb-0">
                                Pastikan GPS ditemukan dan foto wajah berhasil diambil.
                            </p>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<style>
    .camera-frame {
        width: 100%;
        aspect-ratio: 4 / 3;
        background: #111827;
    }

    #cameraVideo {
        display: none;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    #photoPreview {
        display: none;
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: contain;
        background: #111827;
    }

    .attendance-map {
        width: 100%;
        height: 260px;
        border-radius: 12px;
        background: #e5e7eb;
        z-index: 1;
    }

    .leaflet-container {
        font-family: inherit;
    }
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.15/dist/face-api.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('cameraVideo');
    const canvas = document.getElementById('cameraCanvas');
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('cameraPlaceholder');
    const photoInput = document.getElementById('photoInput');
    const startButton = document.getElementById('startCamera');
    const takeButton = document.getElementById('takePhoto');
    const cameraMessage = document.getElementById('cameraMessage');
    const faceAlert = document.getElementById('faceAlert');

    const locationStatus = document.getElementById('locationStatus');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const refreshLocation = document.getElementById('refreshLocation');
    const submitButton = document.getElementById('submitAttendance');
    const form = document.getElementById('attendanceForm');

    if (!form) return;

    let cameraStream = null;
    let locationReady = false;
    let faceModelReady = false;
    let faceDetected = false;
    let photoReady = false;
    let faceVerifiedAtCapture = false;
    let detectionTimer = null;
    let map = null;
    let userMarker = null;
    let pklMarker = null;

    const pklLatitude = @json($placement->location->latitude ?? null);
    const pklLongitude = @json($placement->location->longitude ?? null);
    const pklName = @json($placement->location->name ?? 'Lokasi PKL');
    const pklRadius = Number(@json($placement->location->radius_meter ?? 100));

    function updateSubmitButton() {
        submitButton.disabled = !(locationReady && photoReady && faceVerifiedAtCapture);
    }

    function showFaceStatus(type, message) {
        faceAlert.className = 'alert py-2 small mb-3';

        if (type === 'success') {
            faceAlert.classList.add('alert-success');
            faceAlert.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>' + message;
        } else if (type === 'danger') {
            faceAlert.classList.add('alert-danger');
            faceAlert.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-1"></i>' + message;
        } else {
            faceAlert.classList.add('alert-secondary');
            faceAlert.innerHTML = '<i class="bi bi-info-circle me-1"></i>' + message;
        }
    }

    async function loadFaceModel() {
        if (faceModelReady) return true;

        if (!window.faceapi) {
            showFaceStatus('danger', 'Library deteksi wajah gagal dimuat. Periksa koneksi internet.');
            return false;
        }

        try {
            showFaceStatus('info', 'Sedang memuat sistem deteksi wajah...');

            const modelUrl = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api@1.7.15/model';

            await faceapi.nets.tinyFaceDetector.loadFromUri(modelUrl);

            faceModelReady = true;
            return true;
        } catch (error) {
            console.error('Face model error:', error);
            showFaceStatus('danger', 'Model deteksi wajah gagal dimuat. Muat ulang halaman dan coba lagi.');
            return false;
        }
    }

    async function checkFace() {
        if (!cameraStream || !faceModelReady || video.readyState < 2) return;

        try {
            const detections = await faceapi.detectAllFaces(
                video,
                new faceapi.TinyFaceDetectorOptions({
                    inputSize: 320,
                    scoreThreshold: 0.5
                })
            );

            const found = detections.length > 0;
            faceDetected = found;

            if (found) {
                takeButton.disabled = false;
                showFaceStatus('success', 'Wajah terdeteksi. Kamu sudah bisa mengambil foto.');
            } else {
                takeButton.disabled = true;
                showFaceStatus('danger', 'Wajah tidak terdeteksi. Posisikan wajah di depan kamera dan pastikan pencahayaan cukup.');
            }
        } catch (error) {
            console.error('Face detection error:', error);
            faceDetected = false;
            takeButton.disabled = true;
            showFaceStatus('danger', 'Pemeriksaan wajah mengalami kendala. Coba aktifkan ulang kamera.');
        }
    }

    async function startCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraMessage.textContent = 'Browser tidak mendukung kamera. Gunakan Chrome melalui localhost atau HTTPS.';
            showFaceStatus('danger', 'Akses kamera tidak tersedia di browser ini.');
            return;
        }

        startButton.disabled = true;
        takeButton.disabled = true;
        faceDetected = false;
        photoReady = false;
        faceVerifiedAtCapture = false;
        updateSubmitButton();

        try {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
            }

            if (detectionTimer) {
                clearInterval(detectionTimer);
                detectionTimer = null;
            }

            const modelLoaded = await loadFaceModel();

            if (!modelLoaded) {
                startButton.disabled = false;
                return;
            }

            cameraStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                },
                audio: false
            });

            video.srcObject = cameraStream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            preview.style.display = 'none';

            await video.play();

            cameraMessage.textContent = 'Kamera aktif. Tunggu sampai wajah terdeteksi.';
            showFaceStatus('info', 'Sedang memeriksa wajah...');

            await checkFace();
            detectionTimer = setInterval(checkFace, 500);

        } catch (error) {
            console.error('Camera error:', error);

            let errorMessage = 'Kamera gagal diakses.';

            if (error.name === 'NotAllowedError') {
                errorMessage = 'Izin kamera ditolak. Izinkan akses kamera melalui pengaturan browser.';
            } else if (error.name === 'NotFoundError') {
                errorMessage = 'Kamera tidak ditemukan pada perangkat.';
            } else if (error.name === 'NotReadableError') {
                errorMessage = 'Kamera sedang digunakan aplikasi lain. Tutup aplikasi video call atau aplikasi lain yang memakai kamera.';
            } else if (!window.isSecureContext) {
                errorMessage = 'Akses kamera membutuhkan HTTPS atau localhost.';
            }

            cameraMessage.textContent = errorMessage;
            showFaceStatus('danger', errorMessage);
        } finally {
            startButton.disabled = false;
        }
    }

    startButton.addEventListener('click', startCamera);

    takeButton.addEventListener('click', function () {
        if (!cameraStream || !video.videoWidth || !faceDetected) {
            alert('Wajah belum terdeteksi. Posisikan wajah di depan kamera terlebih dahulu.');
            return;
        }

        takeButton.disabled = true;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;

        const context = canvas.getContext('2d');
        context.save();
        context.translate(canvas.width, 0);
        context.scale(-1, 1);
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        context.restore();

        canvas.toBlob(function (blob) {
            if (!blob) {
                cameraMessage.textContent = 'Gagal mengambil foto. Silakan coba lagi.';
                takeButton.disabled = !faceDetected;
                return;
            }

            if (blob.size > 4 * 1024 * 1024) {
                cameraMessage.textContent = 'Ukuran foto melebihi 4 MB. Coba ambil foto kembali.';
                takeButton.disabled = !faceDetected;
                return;
            }

            const photoFile = new File([blob], 'foto-absensi.jpg', {
                type: 'image/jpeg'
            });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(photoFile);
            photoInput.files = dataTransfer.files;

            preview.src = URL.createObjectURL(blob);
            preview.style.display = 'block';

            photoReady = true;
            faceVerifiedAtCapture = true;
            updateSubmitButton();

            cameraMessage.textContent = 'Foto berhasil diambil. Periksa hasil foto sebelum mengirim absensi.';
            showFaceStatus('success', 'Wajah berhasil terdeteksi dan foto siap dikirim.');
        }, 'image/jpeg', 0.85);
    });

    function initializeMap() {
        const mapElement = document.getElementById('attendanceMap');
        if (!mapElement || !window.L) return;

        map = L.map('attendanceMap').setView([-2.5, 118], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        if (pklLatitude !== null && pklLongitude !== null) {
            const lat = Number(pklLatitude);
            const lng = Number(pklLongitude);

            if (Number.isFinite(lat) && Number.isFinite(lng)) {
                pklMarker = L.marker([lat, lng])
                    .addTo(map)
                    .bindPopup('<strong>' + pklName + '</strong><br>Lokasi PKL');

                L.circle([lat, lng], {
                    radius: Number.isFinite(pklRadius) ? pklRadius : 100,
                    color: '#2563eb',
                    fillOpacity: 0.08
                }).addTo(map);

                map.setView([lat, lng], 15);
            }
        }

        setTimeout(() => map.invalidateSize(), 300);
    }

    function getLocation() {
        locationReady = false;
        updateSubmitButton();

        locationStatus.textContent = 'Sedang mencari lokasi...';

        if (!navigator.geolocation) {
            locationStatus.textContent = 'Browser tidak mendukung GPS.';
            return;
        }

        refreshLocation.disabled = true;

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;

                latitudeInput.value = lat;
                longitudeInput.value = lng;
                locationReady = true;

                locationStatus.innerHTML =
                    '<span class="text-success fw-semibold">' +
                    '<i class="bi bi-check-circle-fill me-1"></i> Lokasi berhasil ditemukan' +
                    '</span><br>Latitude: ' + lat.toFixed(6) +
                    '<br>Longitude: ' + lng.toFixed(6);

                if (map) {
                    if (userMarker) {
                        userMarker.setLatLng([lat, lng]);
                    } else {
                        userMarker = L.marker([lat, lng]).addTo(map);
                    }

                    userMarker.bindPopup('<strong>Posisi Kamu</strong>').openPopup();

                    if (pklMarker) {
                        const bounds = L.latLngBounds([
                            [lat, lng],
                            pklMarker.getLatLng()
                        ]);
                        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 16 });
                    } else {
                        map.setView([lat, lng], 16);
                    }
                }

                updateSubmitButton();
                refreshLocation.disabled = false;
            },
            function (error) {
                locationReady = false;
                updateSubmitButton();
                refreshLocation.disabled = false;

                if (error.code === 1) {
                    locationStatus.textContent = 'Izin lokasi ditolak. Izinkan akses lokasi di browser.';
                } else if (error.code === 2) {
                    locationStatus.textContent = 'Lokasi tidak tersedia. Periksa GPS perangkat.';
                } else {
                    locationStatus.textContent = 'Waktu mencari lokasi habis. Silakan coba lagi.';
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0
            }
        );
    }

    refreshLocation.addEventListener('click', getLocation);

    form.addEventListener('submit', function (event) {
        if (!locationReady) {
            event.preventDefault();
            alert('Lokasi GPS belum berhasil ditemukan.');
            return;
        }

        if (!photoReady || !faceVerifiedAtCapture || !photoInput.files.length) {
            event.preventDefault();
            alert('Ambil foto melalui kamera setelah wajah terdeteksi.');
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML =
            '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim absensi...';
    });

    initializeMap();
    getLocation();

    window.addEventListener('beforeunload', function () {
        if (cameraStream) {
            cameraStream.getTracks().forEach(track => track.stop());
        }

        if (detectionTimer) {
            clearInterval(detectionTimer);
        }
    });
});
</script>
@endpush
