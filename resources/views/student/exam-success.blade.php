<x-app-layout>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white border border-black p-10 text-center relative">

                <div class="flex justify-center mb-8">
                    <div class="border border-black p-4 bg-white">
                        <svg class="w-10 h-10 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-black mb-4 uppercase tracking-tighter">
                    JAWAPAN_DITERIMA
                </h2>
                
                <div class="inline-block border-y border-black border-dotted py-6 mb-8 w-full">
                    <p class="text-[11px] font-bold text-black uppercase leading-relaxed tracking-tight">
                        Tahniah, jawapan anda telah berjaya difailkan ke dalam arkib sistem. 
                        Keputusan rasmi akan diterbitkan oleh pihak pentadbir setelah proses 
                        semakan dan penilaian selesai sepenuhnya.
                    </p>
                </div>

                <div class="bg-[#FDFDFC] border border-black p-4 mb-10 text-left">
                    <div class="flex justify-between text-[10px] border-b border-black border-dotted pb-2 mb-2">
                        <span class="text-gray-400 font-bold uppercase tracking-widest">STATUS_PENYERAHAN:</span>
                        <span class="font-bold text-black uppercase">BERJAYA</span>
                    </div>
                    <div class="flex justify-between text-[10px]">
                        <span class="text-gray-400 font-bold uppercase tracking-widest">TRANSAKSI_ID:</span>
                        <span class="font-bold text-black uppercase">#{{ strtoupper(substr(uniqid(), 0, 8)) }}</span>
                    </div>
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-block px-8 py-3 border border-black text-[10px] font-bold text-black uppercase tracking-widest hover:bg-black hover:text-white transition-all">
                        [ KEMBALI_KE_DASHBOARD ]
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Padam semua memori timer dalam browser bila dah berjaya hantar
    localStorage.clear(); 
    // Atau kalau nak spesifik: localStorage.removeItem('exam_timer_{{ $examId ?? "" }}');
</script>
</x-app-layout>