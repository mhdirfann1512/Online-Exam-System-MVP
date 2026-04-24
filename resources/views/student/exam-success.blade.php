<x-app-layout>
    <div class="min-h-[calc(100vh-64px)] flex items-center justify-center bg-slate-50/50">
        <div class="max-w-2xl mx-auto px-6">
            <div class="bg-white overflow-hidden shadow-2xl shadow-slate-200/60 rounded-[3rem] p-12 md:p-20 text-center border border-slate-100 relative">
                
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-emerald-50 rounded-full blur-3xl opacity-50 -z-10"></div>

                <div class="flex justify-center mb-10">
                    <div class="bg-emerald-500 p-1 rounded-full shadow-lg shadow-emerald-200 animate-bounce-short">
                        <div class="bg-white p-5 rounded-full">
                            <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <h2 class="text-4xl font-black text-slate-800 mb-4 tracking-tight">Peperiksaan Selesai!</h2>
                <div class="space-y-4 mb-12">
                    <p class="text-lg text-slate-600 font-medium leading-relaxed">
                        Tahniah! Jawapan anda telah selamat mendarat dalam sistem kami. 🚀
                    </p>
                    <p class="text-sm text-slate-400 max-w-sm mx-auto italic leading-relaxed">
                        Keputusan akan dikemaskini sebaik sahaja proses penilaian selesai. Sila semak dashboard anda secara berkala.
                    </p>
                </div>

                <div class="flex flex-col items-center gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-10 py-4 bg-slate-900 border border-transparent rounded-2xl font-black text-white text-sm uppercase tracking-widest hover:bg-slate-800 hover:scale-105 active:scale-95 transition-all duration-200 shadow-xl shadow-slate-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Back to Dashboard
                    </a>
                    
                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest mt-4">
                        Mini-LMS Secure Submission System
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes bounce-short {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-short {
            animation: bounce-short 2s ease-in-out infinite;
        }
    </style>
</x-app-layout>