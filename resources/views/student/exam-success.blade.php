<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-black p-12 text-center relative overflow-hidden">
                
                <div class="absolute top-0 right-0 p-2 bg-black text-white text-[10px] font-black uppercase tracking-tighter italic">
                    CONFIRMED_SUBMISSION
                </div>

                <div class="flex justify-center mb-8">
                    <div class="border-4 border-black p-4 rounded-none rotate-3 bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                        <svg class="w-12 h-12 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-4xl font-black text-black mb-4 uppercase tracking-tighter italic">
                    REKOD DITERIMA!
                </h2>
                
                <div class="inline-block border-y-2 border-black border-dotted py-4 mb-8">
                    <p class="text-sm font-bold text-black uppercase leading-relaxed tracking-tight">
                        Tahniah, jawapan anda telah berjaya difailkan ke dalam arkib sistem. <br>
                        Keputusan rasmi akan diterbitkan oleh pihak pentadbir setelah proses <br>
                        semakan dan penilaian selesai sepenuhnya.
                    </p>
                </div>

                <div class="bg-gray-50 border border-black p-4 mb-10 text-left font-mono">
                    <div class="flex justify-between text-[10px] border-b border-gray-300 pb-1 mb-1">
                        <span class="text-gray-500 uppercase">Status Penyerahan:</span>
                        <span class="font-bold text-black uppercase">BERJAYA / SUCCESS</span>
                    </div>
                    <div class="flex justify-between text-[10px]">
                        <span class="text-gray-500 uppercase">ID Transaksi:</span>
                        <span class="font-bold text-black uppercase">#{{ strtoupper(uniqid()) }}</span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-8 py-3 bg-black border-2 border-black text-xs font-black text-white uppercase tracking-[0.2em] hover:bg-white hover:text-black transition-all duration-150 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none active:translate-y-1">
                        [ KEMBALI KE DASHBOARD ]
                    </a>
                </div>

                <p class="mt-12 text-[9px] font-bold text-gray-400 uppercase tracking-widest italic">
                    Sistem Peperiksaan Digital v2.0 // Sessi 2026
                </p>

            </div>
        </div>
    </div>
</x-app-layout>