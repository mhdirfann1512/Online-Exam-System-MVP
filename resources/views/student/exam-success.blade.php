<x-app-layout>
    <div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-[#e5e5e5] font-mono">
        <div class="max-w-2xl mx-auto px-6 w-full">
            <div class="bg-white border-8 border-black shadow-[16px_16px_0px_0px_rgba(0,0,0,1)] p-12 md:p-20 text-center relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-4 bg-yellow-400 border-b-4 border-black"></div>
                <div class="absolute bottom-0 left-0 w-full h-4 bg-black"></div>

                <div class="flex justify-center mb-12">
                    <div class="bg-emerald-400 p-1 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] animate-bounce-custom">
                        <div class="bg-white p-5 border-2 border-black">
                            <svg class="w-16 h-16 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl font-black text-black mb-6 tracking-tighter uppercase italic">
                        SUBMISSION_COMPLETE
                    </h2>
                    
                    <div class="space-y-6 mb-12">
                        <p class="text-lg text-black font-bold leading-none bg-yellow-400 inline-block px-4 py-2 border-2 border-black uppercase tracking-tighter">
                            Data anda telah selamat didaftarkan ke dalam server. 🛰️
                        </p>
                        <p class="text-xs text-slate-500 max-w-sm mx-auto font-bold leading-relaxed uppercase tracking-widest border-t-2 border-black border-dashed pt-6">
                            Sistem sedang memproses penilaian. Sila pantau dashboard anda untuk status keputusan terkini.
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-6">
                        <a href="{{ route('dashboard') }}" 
                           class="group relative inline-flex items-center px-12 py-5 bg-black text-yellow-400 font-black text-lg uppercase tracking-tighter transition-all hover:shadow-none hover:translate-x-2 hover:translate-y-2 shadow-[8px_8px_0px_0px_rgba(0,0,0,0.2)]">
                            <svg class="w-5 h-5 mr-3 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            RETURN_TO_BASE
                        </a>
                        
                        <div class="mt-4 flex items-center space-x-2">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                            <p class="text-[10px] font-black text-black uppercase tracking-[0.3em]">
                                SYSTEM_SECURED // ENCRYPTED_RECEIPT_ID: {{ rand(1000, 9999) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-yellow-400 rotate-45 border-4 border-black"></div>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-custom {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }
        .animate-bounce-custom {
            animation: bounce-custom 1.5s cubic-bezier(0.68, -0.55, 0.27, 1.55) infinite;
        }
    </style>
</x-app-layout>