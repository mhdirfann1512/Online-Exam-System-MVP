<x-app-layout>
    <x-slot name="header">
        <div class="max-w-5xl mx-auto border-b-4 border-black pb-4 font-mono">
            <div class="flex justify-between items-center">
                <h2 class="font-black text-xl text-black tracking-tighter uppercase italic">
                    EXAM_REPORT // {{ $exam->title }}
                </h2>
                <div class="px-3 py-1 bg-black text-yellow-400 text-[10px] font-black tracking-widest uppercase border-2 border-black">
                    FINAL_SCORE
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-[#e5e5e5] min-h-screen font-mono">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] p-6 md:p-10 mb-10 relative">
                <div class="relative z-10 flex flex-col md:flex-row items-center gap-8 md:gap-12">
                    <div class="inline-flex flex-col items-center justify-center w-32 h-32 bg-black text-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(34,197,94,1)]">
                        <span class="text-4xl font-black">{{ round($submission->score) }}</span>
                        <span class="text-[8px] font-black tracking-widest text-emerald-400 uppercase">Percent</span>
                    </div>
                    
                    <div class="flex-grow">
                        <h3 class="text-[10px] font-black text-black uppercase tracking-[0.2em] mb-6 border-b-2 border-black inline-block pb-1 italic">Summary_Metrics</h3>
                        <div class="grid grid-cols-2 gap-8">
                            <div class="border-l-4 border-emerald-500 pl-4 py-1">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Correct</p>
                                <p class="text-2xl font-black text-black leading-tight">{{ $submission->correct_answers }} / {{ $submission->total_questions }}</p>
                            </div>
                            <div class="border-l-4 border-black pl-4 py-1">
                                <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Status</p>
                                <p class="text-2xl font-black text-black leading-tight uppercase italic tracking-tighter">Validated</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex items-center space-x-4 px-2">
                    <h4 class="font-black text-black uppercase tracking-[0.2em] text-[11px] whitespace-nowrap">Review_Log</h4>
                    <div class="h-1 flex-grow bg-black"></div>
                </div>

                @foreach($exam->questions as $index => $q)
                    @php
                        $studentAns = $submission->answers[$q->id] ?? 'N/A';
                        $isCorrect = false;
                        if($q->type == 'mcq') {
                            $isCorrect = strtoupper($studentAns) == strtoupper($q->correct_answer);
                        } else {
                            $isCorrect = str_contains(strtolower($studentAns), strtolower(explode(',', $q->correct_answer)[0]));
                        }
                    @endphp

                    <div class="bg-white border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] transition-all">
                        <div class="flex flex-col md:flex-row">
                            <div class="w-full md:w-16 flex flex-col items-center justify-center font-black text-xl border-b-4 md:border-b-0 md:border-r-4 border-black
                                {{ $isCorrect ? 'bg-emerald-400 text-black' : 'bg-red-400 text-black' }}">
                                <span class="text-[8px] uppercase tracking-tighter mb-0.5">UNIT</span>
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-6 gap-4">
                                    <p class="text-lg font-black text-black leading-tight max-w-2xl">
                                        {{ $q->question_text }}
                                    </p>
                                    @if($isCorrect)
                                        <div class="px-2 py-0.5 bg-black text-emerald-400 text-[8px] font-black uppercase tracking-widest flex-none">PASS</div>
                                    @else
                                        <div class="px-2 py-0.5 bg-black text-red-500 text-[8px] font-black uppercase tracking-widest flex-none">FAIL</div>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-2 border-black">
                                    <div class="p-4 {{ $isCorrect ? 'bg-emerald-50' : 'bg-red-50' }} border-b-2 md:border-b-0 md:border-r-2 border-black">
                                        <p class="text-[8px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 italic">STUDENT_INPUT</p>
                                        <p class="font-black text-black uppercase text-sm leading-relaxed italic">"{{ $studentAns }}"</p>
                                    </div>

                                    <div class="p-4 bg-white">
                                        <p class="text-[8px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2 italic">SYSTEM_VALUE</p>
                                        @if($isCorrect)
                                            <p class="text-emerald-600 font-black text-[10px] uppercase tracking-widest">VERIFIED_MATCH</p>
                                        @else
                                            <div class="inline-block bg-black text-white px-3 py-0.5 text-xs font-black uppercase tracking-tighter">
                                                {{ $q->correct_answer }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center pb-20">
                <a href="{{ route('dashboard') }}" class="group px-8 py-4 bg-black text-yellow-400 border-4 border-black font-black text-sm uppercase tracking-tighter shadow-[6px_6px_0px_0px_rgba(0,0,0,0.2)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                    << BACK_TO_DASHBOARD
                </a>
            </div>
        </div>
    </div>
</x-app-layout>