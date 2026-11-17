@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-6 px-4" data-aos="fade-up">
    
    <!-- Kartu Identitas -->
    <div class="bg-gradient-to-br from-blue-600 to-indigo-800 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden mb-6">
        <!-- Ornamen Latar -->
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white opacity-10 rounded-full"></div>
        
        <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6 relative z-10">
            <!-- Avatar -->
            <div class="relative group" x-data="{ showPhotoModal: false }">
                <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=random' }}" 
                     @click="showPhotoModal = true"
                     class="w-24 h-24 rounded-full border-4 border-white/30 object-cover shadow-lg cursor-pointer transition-transform group-hover:scale-105">
                
                <!-- Modal Foto Besar -->
                <div x-show="showPhotoModal" x-cloak @click="showPhotoModal = false" 
                     class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 cursor-zoom-out">
                    <img src="{{ auth()->user()->photo ? asset('storage/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" class="max-w-full max-h-full rounded-xl shadow-2xl">
                </div>
            </div>

            <!-- Info Utama -->
            <div class="text-center md:text-left flex-1">
                <h1 class="text-2xl font-bold">{{ auth()->user()->name }}</h1>
                <p class="text-blue-200 text-sm font-medium capitalize mt-1">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                <div class="mt-3 inline-flex items-center space-x-2 bg-white/20 px-3 py-1.5 rounded-full text-xs backdrop-blur-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span>Bergabung {{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Edit Profil -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-gray-800">Informasi Pribadi</h2>
            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-md">Aktif</span>
        </div>

        <form x-data="profileForm()" @submit.prevent="submitForm" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" x-model="form.name" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email</label>
                    <input type="email" x-model="form.email" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nomor HP</label>
                    <input type="text" x-model="form.phone" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Password Baru (Opsional)</label>
                    <input type="password" x-model="form.password" placeholder="••••••••" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5">
                </div>
            </div>
            
            <div class="flex justify-end pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl font-semibold text-sm hover:bg-blue-700 transition transform hover:-translate-y-0.5 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Notifikasi Sukses Animasi -->
<div x-data="{ showSuccess: false }" @profile-success.window="showSuccess = true; setTimeout(() => showSuccess = false, 3000)">
    <div x-show="showSuccess" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-50 translate-y-10"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-50"
         class="fixed inset-0 flex items-center justify-center z-[200] pointer-events-none">
        <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center border border-gray-100">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Berhasil!</h3>
            <p class="text-gray-500 text-sm mt-1 text-center">Profil Berhasil Diperbarui.</p>
        </div>
    </div>
</div>

<script>
function profileForm() {
    return {
        form: {
            name: '{{ auth()->user()->name }}',
            email: '{{ auth()->user()->email }}',
            phone: '{{ auth()->user()->phone ?? "" }}',
            password: ''
        },
        submitForm() {
            // Simulasi pengiriman data (Ganti dengan fetch API sesungguhnya)
            // fetch('/api/profile', { method: 'POST', ... })
            
            // Trigger animasi sukses
            window.dispatchEvent(new CustomEvent('profile-success'));
        }
    }
}
</script>
@endsection