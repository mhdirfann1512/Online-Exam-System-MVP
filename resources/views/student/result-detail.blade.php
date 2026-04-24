<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-4xl mx-auto">
            <h2 class="font-bold text-xl text-slate-800 tracking-tight">
                Laporan Keputusan
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors">
                Tutup & Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 mb-10 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-50 rounded-bl-full opacity-50"></div>
                
                <div class="relative z-10">
                    <p class="text-xs font-black text-indigo-500 uppercase tracking-[0.2em] mb-2">{{ $exam->title }}</p>
                    <h3 class="text-3xl font-black text-slate-800 mb-8">Prestasi Anda</h3>
                    
                    <div class="flex flex-col md:flex-row md:items-center gap-8">
                        <div class="inline-flex items-center justify-center w-32 h-32 rounded-3xl bg-slate-900 text-white shadow-2xl shadow-slate-300">
                            <span class="text-4xl font-black">{{ round($submission->score) }}<span class="text-sm text-slate-400">%</span></span>
                        </div>
                        
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <span class="flex-none w-2 h-2 rounded-full bg-emerald-500"></span>
                                <p class="text-slate-600 font-medium">
                                    <span class="font-black text-slate-900">{{ $submission->correct_answers }}</span> Jawapan Betul
                                </p>
                            </div>
                            <div class="flex items-center space-x-3">
                                <span class="flex-none w-2 h-2 rounded-full bg-slate-200"></span>
                                <p class="text-slate-600 font-medium">
                                    Daripada <span class="font-black text-slate-900">{{ $submission->total_questions }}</span> jumlah soalan
                                </p>
                            </div>
                            <p class="text-xs text-slate-400 mt-4 uppercase tracking-widest font-bold">Status: Disemak Automatik</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Semakan Soalan</h4>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">Scroll untuk lihat semua</span>
                </div>

                @foreach($exam->questions as $index => $q)
                    @php
                        $studentAns = $submission->answers[$q->id] ?? 'Tiada Jawapan';
                        $isCorrect = false;
                        
                        if($q->type == 'mcq') {
                            $isCorrect = strtoupper($studentAns) == strtoupper($q->correct_answer);
                        } else {
                            // Logic ringkas matching untuk UI review
                            $isCorrect = str_contains(strtolower($studentAns), strtolower(explode(',', $q->correct_answer)[0]));
                        }
                    @endphp

                    <div class="group bg-white border {{ $isCorrect ? 'border-emerald-100' : 'border-red-100' }} rounded-[2rem] p-6 md:p-8 transition-all hover:shadow-lg">
                        <div class="flex items-start gap-4">
                            <div class="flex-none w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm {{ $isCorrect ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                                {{ $index + 1 }}
                            </div>
                            
                            <div class="flex-grow">
                                <p class="text-lg font-bold text-slate-800 leading-tight mb-6">
                                    {{ $q->question_text }}
                                </p>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="p-4 rounded-2xl {{ $isCorrect ? 'bg-emerald-50/50' : 'bg-red-50/50' }}">
                                        <p class="text-[10px] font-black uppercase tracking-widest {{ $isCorrect ? 'text-emerald-600' : 'text-red-600' }} mb-1">
                                            Jawapan Anda
                                        </p>
                                        <p class="font-bold text-slate-700">{{ $studentAns }}</p>
                                    </div>

                                    @if(!$isCorrect)
                                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                                Jawapan Sebenar
                                            </p>
                                            <p class="font-bold text-slate-900">{{ $q->correct_answer }}</p>
                                        </div>
                                    @else
                                        <div class="p-4 flex items-center text-emerald-600">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Tepat Sekali!</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center pb-10">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-8 py-4 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm">
                    Kembali ke Dashboard Utama
                </a>
            </div>
        </div>
    </div>
</x-app-layout>