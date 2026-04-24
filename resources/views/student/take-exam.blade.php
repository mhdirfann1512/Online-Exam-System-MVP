<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-black p-4 border-x border-t border-black">
            <h2 class="font-bold text-lg text-white uppercase tracking-tighter italic">
                PEPERIKSAAN: {{ $exam->title }}
            </h2>
            <div class="flex items-center gap-4">
                <span class="text-[10px] text-gray-400 font-mono uppercase">Masa Berbaki:</span>
                <div id="timer" class="text-yellow-400 font-mono font-bold text-3xl tracking-tighter leading-none italic">--:--</div>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-100 min-h-screen" oncopy="return false" onpaste="return false">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-0">
            
            <div class="md:w-3/4 bg-white border-2 border-black p-8 relative">
                <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                    @csrf
                    @foreach($exam->questions as $index => $q)
                        <div class="question-container {{ $index == 0 ? '' : 'hidden' }}" id="q-{{ $index }}" data-id="{{ $q->id }}">
                            
                            <div class="flex justify-between items-end mb-10 border-b-2 border-black pb-4">
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-gray-500 tracking-[0.2em]">Kertas Soalan Digital</span>
                                    <h3 class="text-2xl font-black uppercase tracking-tighter">Unit Soalan {{ $index + 1 }} / {{ $exam->questions->count() }}</h3>
                                </div>
                                <button type="button" onclick="toggleFlag({{ $q->id }}, {{ $index }})" 
                                        id="flag-btn-{{ $index }}" 
                                        class="text-[10px] font-bold uppercase px-4 py-2 border-2 border-black transition-colors {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-yellow-400 text-black' : 'bg-white text-black hover:bg-gray-100' }}">
                                    🚩 TANDA SEMAKAN (FLAG)
                                </button>
                            </div>
                            
                            <div class="mb-12">
                                <p class="text-xl font-bold leading-snug text-black uppercase tracking-tight italic">{{ $q->question_text }}</p>
                            </div>

                            @if($q->type == 'mcq')
                                <div class="grid grid-cols-1 gap-4">
                                    @foreach($q->options as $key => $value)
                                        <label class="group flex items-center p-4 border-2 border-black cursor-pointer hover:bg-black hover:text-white transition-all">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" 
                                                   class="w-5 h-5 border-2 border-black text-black focus:ring-0"
                                                   {{ ($submission->answers[$q->id] ?? '') == $key ? 'checked' : '' }}
                                                   onchange="autoSave({{ $q->id }}, '{{ $key }}', {{ $index }})">
                                            <div class="ml-4 flex gap-2 text-sm uppercase font-bold tracking-tight">
                                                <span class="font-black">[{{ $key }}]</span> 
                                                <span>{{ $value }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="border-2 border-black p-2">
                                    <textarea name="answers[{{ $q->id }}]" 
                                              class="w-full border-0 p-4 text-sm font-mono uppercase bg-gray-50 focus:ring-0 focus:bg-white transition-colors" 
                                              placeholder="SILA TAIP JAWAPAN ANDA DI SINI..."
                                              rows="8"
                                              onblur="autoSave({{ $q->id }}, this.value, {{ $index }})">{{ $submission->answers[$q->id] ?? '' }}</textarea>
                                </div>
                            @endif

                            <div class="flex justify-between mt-12 pt-6 border-t border-black border-dotted">
                                <button type="button" onclick="prevQ({{ $index }})" 
                                        class="text-xs font-black uppercase underline hover:no-underline {{ $index == 0 ? 'invisible' : '' }}">
                                    << KEMBALI
                                </button>
                                
                                @if($index + 1 == $exam->questions->count())
                                    <button type="submit" class="bg-black text-white px-10 py-3 text-sm font-black uppercase tracking-widest hover:bg-red-600 transition-colors">
                                        [ HANTAR SEKARANG ]
                                    </button>
                                @else
                                    <button type="button" onclick="nextQ({{ $index }})" 
                                            class="text-xs font-black uppercase underline hover:no-underline">
                                        SOALAN SETERUSNYA >>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="md:w-1/4 bg-black p-1 border-y md:border-y-0 md:border-r border-black">
                <div class="p-6 bg-white border border-black h-full">
                    <h3 class="text-[10px] font-black uppercase tracking-[0.2em] mb-6 border-b border-black pb-2 text-center">Peta Navigasi Unit</h3>
                    
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($exam->questions as $index => $q)
                            <button type="button" id="nav-{{ $index }}" 
                                    onclick="showQ({{ $index }})"
                                    class="w-full aspect-square border-2 border-black text-xs font-black flex items-center justify-center transition-all
                                    {{ ($submission->answers[$q->id] ?? '') ? 'bg-black text-white' : 'bg-white text-black' }}
                                    {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-yellow-400 !text-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]' : '' }}">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-10 space-y-3 pt-6 border-t border-black border-dashed">
                        <div class="flex items-center text-[9px] font-black uppercase">
                            <span class="w-4 h-4 bg-black mr-3 border border-black"></span> DIJAWAB
                        </div>
                        <div class="flex items-center text-[9px] font-black uppercase">
                            <span class="w-4 h-4 bg-yellow-400 mr-3 border border-black shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]"></span> SEMAKAN (FLAG)
                        </div>
                        <div class="flex items-center text-[9px] font-black uppercase">
                            <span class="w-4 h-4 bg-white mr-3 border border-black"></span> BELUM JAWAB
                        </div>
                    </div>

                    <div class="mt-8 p-3 bg-gray-100 border border-black">
                        <p class="text-[8px] leading-tight font-bold uppercase text-gray-500 italic text-center">
                            Sila pastikan semua unit dijawab sebelum menghantar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Timer Logic (Styling change only)
        let duration = {{ $exam->duration_minutes }} * 60;
        const timerDisplay = document.getElementById('timer');

        const countdown = setInterval(() => {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            timerDisplay.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (duration <= 0) {
                clearInterval(countdown);
                alert("MASA TAMAT! Jawapan anda akan dihantar secara automatik.");
                document.getElementById('examForm').submit();
            }
            duration--;
        }, 1000);

        // 2. Anti-Cheat (No styling change needed)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log("LOG: Pelajar menukar tab.");
            }
        });

        let currentQ = 0;
        const totalQ = {{ $exam->questions->count() }};

        function showQ(index) {
            document.querySelectorAll('.question-container').forEach(el => el.classList.add('hidden'));
            document.getElementById(`q-${index}`).classList.remove('hidden');
            currentQ = index;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function nextQ(index) { if(index < totalQ - 1) showQ(index + 1); }
        function prevQ(index) { if(index > 0) showQ(index - 1); }

        function autoSave(qId, value, index) {
            const data = { answers: { [qId]: value }, _token: '{{ csrf_token() }}' };
            fetch("{{ route('student.exams.auto-save', $exam->id) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            }).then(() => {
                const nav = document.getElementById(`nav-${index}`);
                nav.classList.add('bg-black', 'text-white');
            });
        }

        function toggleFlag(qId, index) {
            fetch("{{ route('student.exams.toggle-flag', $exam->id) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ question_id: qId })
            })
            .then(res => res.json())
            .then(data => {
                const btn = document.getElementById(`flag-btn-${index}`);
                const nav = document.getElementById(`nav-${index}`);
                
                if (data.flagged.includes(qId)) {
                    btn.classList.add('bg-yellow-400');
                    nav.classList.add('bg-yellow-400', 'shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]');
                    nav.classList.remove('text-white');
                } else {
                    btn.classList.remove('bg-yellow-400');
                    nav.classList.remove('bg-yellow-400', 'shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]');
                    // Re-check if answered
                    const form = new FormData(document.getElementById('examForm'));
                    if (form.get(`answers[${qId}]`)) {
                        nav.classList.add('bg-black', 'text-white');
                    }
                }
            });
        }
    </script>
</x-app-layout>