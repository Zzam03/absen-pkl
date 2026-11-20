<div class="fixed bottom-0 w-full bg-white border-t border-gray-200 flex justify-around p-3 md:hidden z-50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
    <a href="/dashboard" class="flex flex-col items-center {{ request()->is('dashboard') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-600' }} transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span class="text-xs mt-1 font-medium">Home</span>
    </a>
    <a href="/absen" class="flex flex-col items-center {{ request()->is('absen*') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-600' }} transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
        <span class="text-xs mt-1 font-medium">Absen</span>
    </a>
    <a href="/profil" class="flex flex-col items-center {{ request()->is('profil*') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-600' }} transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span class="text-xs mt-1 font-medium">Profil</span>
    </a>
</div>