<x-app-layout>
    <div class="py-8 bg-white min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 border-b border-black pb-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Dokumen Rasmi // Laporan Prestasi</span>
                <h2 class="text-lg font-bold text-black uppercase tracking-tight mt-1">
                    {{ $exam->title }}
                </h2>
            </div>

            <div class="mb-12 border border-black p-6 flex flex-col md:flex-row justify-between items-center">
                <div class="text-center md:text-left mb-4 md:mb-0">
                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-widest">MARKAH_KESELURUHAN</span>
                    <p class="text-3xl font-bold text-black tracking-tighter">{{ $submission->score }}%</p>
                </div>
                <div class="text-center md:text-right border-t md:border-t-0 md:border-l border-black border-dotted pt-4 md:pt-0 md:pl-8">
                    <p class="text-[10px] font-bold uppercase text-black">
                        STATUS: {{ $submission->correct_answers }} / {{ $submission->total_questions }} UNIT_LULUS
                    </p>
                    <p class="text-[9px] text-gray-400 uppercase mt-1 italic-none">
                        *ID_SUBMISSION: {{ strtoupper(substr($submission->id, 0, 8)) }}
                    </p>
                </div>
            </div>

            <div class="space-y-0">
                @foreach($exam->questions as $index => $q)
                    @php
                        $studentAns = $submission->answers[$q->id] ?? 'Tiada Jawapan';
                        $isCorrect = false;
                        
                        if($q->type == 'mcq') {
                            $isCorrect = strtoupper($studentAns) == strtoupper($q->correct_answer);
                        } else {
                            $isCorrect = str_contains(strtolower($studentAns), strtolower(explode(',', $q->correct_answer)[0]));
                        }
                    @endphp

                    <div class="py-8 border-b border-black last:border-b-0">
                        <div class="mb-3 flex justify-between items-center">
                            <span class="text-[9px] font-mono text-gray-400 uppercase">ITEM_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[10px] font-bold uppercase {{ $isCorrect ? 'text-black' : 'text-red-600' }}">
                                [ {{ $isCorrect ? 'STATUS: BETUL' : 'STATUS: SALAH' }} ]
                            </span>
                        </div>

                        <div class="mb-6">
                            <p class="text-sm font-bold text-black uppercase leading-tight">{{ $q->question_text }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <span class="text-[9px] font-bold uppercase text-gray-400 tracking-widest block mb-1">INPUT_PELAJAR:</span>
                                <p class="text-xs font-bold uppercase {{ $isCorrect ? 'text-black' : 'text-red-600' }}">
                                    {{ $studentAns }}
                                </p>
                            </div>
                            
                            @if(!$isCorrect)
                                <div class="border-l border-black border-dotted pl-8">
                                    <span class="text-[9px] font-bold uppercase text-gray-400 tracking-widest block mb-1">SKEMA_SISTEM:</span>
                                    <p class="text-xs font-bold uppercase text-black underline underline-offset-4">
                                        {{ $q->correct_answer }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 text-center">
                <p class="text-[9px] font-bold text-gray-300 uppercase tracking-[0.6em]">
                    END_OF_LINE
                </p>
            </div>     

            <div class="mt-16 pt-6 flex flex-col md:flex-row justify-between items-center gap-6">
                <a href="{{ route('student.dashboard') }}" 
                   class="text-[10px] font-bold uppercase underline underline-offset-8 hover:no-underline tracking-widest text-black">
                    << KEMBALI_KE_DASHBOARD
                </a>
                <button onclick="window.print()" class="text-[10px] font-bold uppercase px-4 py-2 border border-black hover:bg-black hover:text-white transition-all">
                    [ CETAK_LAPORAN ]
                </button>
            </div>


        </div>
    </div>
</x-app-layout>