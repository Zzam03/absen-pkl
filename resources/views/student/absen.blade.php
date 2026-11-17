@extends('layouts.app')

@section('content')
<div x-data="attendanceApp()" x-init="initCamera(); initLocation()" class="p-4 max-w-md mx-auto" data-aos="fade-up">
    <div class="mb-6">
        <h2 class="text-2xl font-bold mb-1 text-gray-800">Absensi PKL</h2>
        <p class="text-gray-500 text-sm">Pastikan wajah Anda terlihat jelas dan GPS aktif.</p>
    </div>

    <!-- Kamera Tampilan -->
    <div class="relative rounded-2xl overflow-hidden shadow-xl bg-black aspect-square mb-6 border-4 border-white">
        <video x-ref="video" autoplay playsinline class="w-full h-full object-cover"></video>
        <canvas x-ref="canvas" class="hidden"></canvas>
        
        <!-- Overlay Garis Panduan -->
        <div class="absolute inset-0 border-[40px] border-black/30 rounded-full pointer-events-none"></div>

        <div class="absolute bottom-4 left-0 right-0 flex justify-center">
            <button @click="takeSnapshot()" class="w-16 h-16 bg-white rounded-full border-4 border-gray-300 active:scale-90 transition-transform shadow-lg flex items-center justify-center">
                <div class="w-12 h-12 bg-blue-600 rounded-full"></div>
            </button>
        </div>
    </div>

    <!-- Info Lokasi -->
    <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-6 flex items-start space-x-3 shadow-sm">
        <svg class="w-6 h-6 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        <div class="flex-1">
            <p class="font-semibold text-blue-900 text-sm">Lokasi Anda Saat Ini:</p>
            <p class="text-xs text-blue-700 mt-1" x-text="locationStatus">Mengambil lokasi...</p>
        </div>
    </div>

    <!-- Tombol Absen -->
    <button @click="submitAttendance()" :disabled="!photoTaken || !locationReady" 
            class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 disabled:opacity-50 transition-all transform hover:-translate-y-1 flex justify-center items-center space-x-2">
        <svg x-show="!photoTaken || !locationReady" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        <span x-text="(!photoTaken || !locationReady) ? 'Menunggu Kamera & GPS...' : 'Absen Masuk Sekarang'"></span>
    </button>
</div>

<script>
function attendanceApp() {
    return {
        stream: null, 
        photoTaken: false, 
        photoData: null, 
        locationReady: false,
        lat: null, 
        lng: null, 
        locationStatus: 'Mengambil lokasi...',

        async initCamera() {
            try {
                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" } });
                this.$refs.video.srcObject = this.stream;
            } catch (err) { 
                alert("Kamera gagal diakses. Pastikan Anda memberi izin browser."); 
            }
        },

        initLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.lat = position.coords.latitude;
                        this.lng = position.coords.longitude;
                        this.locationReady = true;
                        this.locationStatus = `Terkunci: ${this.lat.toFixed(5)}, ${this.lng.toFixed(5)}`;
                    },
                    (error) => { 
                        this.locationStatus = 'Gagal mengambil lokasi. Izinkan akses GPS.'; 
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                this.locationStatus = 'Browser Anda tidak mendukung Geolocation.';
            }
        },

        takeSnapshot() {
            const video = this.$refs.video; 
            const canvas = this.$refs.canvas;
            canvas.width = video.videoWidth; 
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
            this.photoData = canvas.toDataURL('image/jpeg', 0.8);
            this.photoTaken = true;
        },

        async submitAttendance() {
            if(!this.photoTaken || !this.locationReady) return;
            
            // Tampilkan loading
            Swal.fire({
                title: 'Memproses Absensi...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading() }
            });

            try {
                const response = await fetch('/api/attendance', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ 
                        latitude: this.lat, 
                        longitude: this.lng, 
                        photo: this.photoData 
                    })
                });
                
                const result = await response.json();
                
                if(response.ok) { 
                    Swal.fire({
                        icon: 'success',
                        title: 'Absensi Berhasil!',
                        text: result.message,
                        confirmButtonColor: '#3b82f6'
                    }).then(() => { window.location.reload(); });
                } else { 
                    Swal.fire({
                        icon: 'error',
                        title: 'Absensi Gagal',
                        text: result.message,
                        confirmButtonColor: '#ef4444'
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Sistem',
                    text: 'Terjadi kesalahan jaringan, coba lagi.',
                });
            }
        }
    }
}
</script>
@endsection