{{-- resources/views/components/alert.blade.php --}}
@if(session('success'))
    <div class="fixed top-6 right-6 z-50 animate-slide-in-right">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-5 rounded-2xl shadow-2xl flex items-center gap-4 transform hover:scale-105 transition-all duration-300">
            <i class="fas fa-check-circle text-4xl"></i>
            <div>
                <p class="font-bold text-xl">Berhasil!</p>
                <p class="text-lg">{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-6 text-white/80 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="fixed top-6 right-6 z-50 animate-slide-in-right">
        <div class="bg-gradient-to-r from-red-500 to-rose-600 text-white px-8 py-5 rounded-2xl shadow-2xl flex items-center gap-4 transform hover:scale-105 transition-all duration-300">
            <i class="fas fa-exclamation-triangle text-4xl"></i>
            <div>
                <p class="font-bold text-xl">Gagal!</p>
                <p class="text-lg">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-6 text-white/80 hover:text-white">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
    </div>
@endif

{{-- Animasi masuk dari kanan --}}
<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out forwards;
    }
</style>
