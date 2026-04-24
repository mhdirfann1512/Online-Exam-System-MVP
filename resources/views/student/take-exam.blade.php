<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto border-b-4 border-black pb-4">
            <div class="flex items-center space-x-4">
                <div class="bg-black text-yellow-400 px-3 py-1 border-2 border-black font-black text-xs tracking-widest font-mono">LIVE_EXAM</div>
                <h2 class="font-black text-2xl text-black tracking-tighter uppercase font-mono italic">{{ $exam->title }}</h2>
            </div>
            <div id="timer-container" class="flex items-center bg-black border-4 border-black px-6 py-2 shadow-[4px_4px_0px_0px_rgba(255,255,0,1)] transition-colors duration-300">
                <svg class="w-5 h-5 mr-3 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div id="timer" class="text-white font-black text-2xl tabular-nums tracking-widest font-mono">--:--</div>
            </div>
        </div>
    </x-slot>

    <div class="w-full bg-black h-4 border-b-4 border-black">
        <div id="progress-bar" class="bg-yellow-400 h-full transition-all duration-500 border-r-4 border-black" style="width: 0%"></div>
    </div>

    <div class="py-10 bg-[#e5e5e5] min-h-screen font-mono" oncopy="return false" onpaste="return false" oncontextmenu="return false">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
            
            <div class="lg:w-3/4">
                <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                    @csrf
                    @foreach($exam->questions as $index => $q)
                        <div class="question-container {{ $index == 0 ? '' : 'hidden' }} space-y-8" id="q-{{ $index }}" data-id="{{ $q->id }}">
                            
                            <div class="p-8 md:p-12 bg-white border-4 border-black shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] relative overflow-hidden">
                                <div class="flex justify-between items-center mb-12 border-b-2 border-black border-dashed pb-6">
                                    <span class="px-4 py-1 bg-black text-white text-[10px] font-black tracking-[0.2em] uppercase">
                                        UNIT_{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} // TOTAL_{{ str_pad($exam->questions->count(), 2, '0', STR_PAD_LEFT) }}
                                    </span>
                                    
                                    <button type="button" onclick="toggleFlag({{ $q->id }}, {{ $index }})" 
                                            id="flag-btn-{{ $index }}" 
                                            class="flex items-center space-x-2 text-[10px] font-black px-4 py-2 border-4 border-black transition-none uppercase
                                            {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-yellow-400 text-black shadow-none translate-x-1 translate-y-1' : 'bg-white text-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:bg-slate-50' }}">
                                        <span class="text-xs">🚩</span>
                                        <span>{{ in_array($q->id, $submission->flagged_questions ?? []) ? 'Status: Flagged' : 'Mark for Review' }}</span>
                                    </button>
                                </div>
                                
                                <h3 class="text-2xl md:text-4xl mb-12 font-black text-black leading-[1.1] tracking-tighter">
                                    {{ $q->question_text }}
                                </h3>

                                @if($q->type == 'mcq')
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($q->options as $key => $value)
                                            <label class="group relative flex items-center p-6 border-4 border-black cursor-pointer transition-none bg-white hover:bg-yellow-50 has-[:checked]:bg-black has-[:checked]:text-white">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" 
                                                       class="hidden peer"
                                                       {{ ($submission->answers[$q->id] ?? '') == $key ? 'checked' : '' }}
                                                       onchange="autoSave({{ $q->id }}, '{{ $key }}', {{ $index }})">
                                                
                                                <div class="flex items-center w-full">
                                                    <span class="flex items-center justify-center w-10 h-10 border-2 border-black font-black text-lg mr-5 uppercase transition-none
                                                        group-hover:bg-black group-hover:text-white 
                                                        peer-checked:bg-yellow-400 peer-checked:text-black peer-checked:border-yellow-400">
                                                        {{ $key }}
                                                    </span>
                                                    <span class="text-xl font-bold tracking-tight">{{ $value }}</span>
                                                </div>
                                                
                                                <div class="hidden peer-checked:block ml-auto">
                                                    <div class="w-4 h-4 bg-yellow-400 border-2 border-black rotate-45"></div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="relative">
                                        <textarea name="answers[{{ $q->id }}]" 
                                                  class="w-full border-4 border-black focus:ring-0 p-8 text-xl text-black font-bold placeholder-slate-300 transition-none bg-slate-50" 
                                                  rows="8" placeholder="TERMINAL_INPUT: Type your technical response here..."
                                                  onblur="autoSave({{ $q->id }}, this.value, {{ $index }})">{{ $submission->answers[$q->id] ?? '' }}</textarea>
                                        <div class="absolute bottom-4 right-4 text-[9px] font-black text-slate-400 uppercase tracking-widest animate-pulse">// AUTO_SYNC_ENABLED</div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-between items-center pt-4">
                                <button type="button" onclick="prevQ({{ $index }})" 
                                        class="flex items-center px-10 py-5 bg-white border-4 border-black text-black font-black uppercase text-xs tracking-widest hover:bg-black hover:text-white transition-none shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1 {{ $index == 0 ? 'invisible' : '' }}">
                                    [ PREV_UNIT ]
                                </button>
                                
                                @if($index + 1 == $exam->questions->count())
                                    <button type="submit" class="px-12 py-5 bg-emerald-400 border-4 border-black text-black font-black text-xl uppercase tracking-tighter hover:bg-black hover:text-emerald-400 transition-none shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1">
                                        EXECUTE_FINAL_SUBMIT
                                    </button>
                                @else
                                    <button type="button" onclick="nextQ({{ $index }})" 
                                            class="px-12 py-5 bg-black text-white border-4 border-black font-black text-xl uppercase tracking-tighter hover:bg-yellow-400 hover:text-black transition-none shadow-[8px_8px_0px_0px_rgba(255,255,0,1)] active:shadow-none active:translate-x-1 active:translate-y-1">
                                        NEXT_UNIT >>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="lg:w-1/4">
                <div class="p-8 bg-white border-4 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] sticky top-8">
                    <h3 class="font-black text-black mb-8 uppercase tracking-[0.2em] text-[11px] border-b-2 border-black pb-2 italic">Unit_Navigation_Matrix</h3>
                    
                    <div class="grid grid-cols-4 gap-3">
                        @foreach($exam->questions as $index => $q)
                            <button type="button" id="nav-{{ $index }}" 
                                    onclick="showQ({{ $index }})"
                                    class="w-full aspect-square border-2 border-black text-sm font-black flex items-center justify-center transition-none relative
                                    {{ ($submission->answers[$q->id] ?? '') ? 'bg-black text-white' : 'bg-white text-slate-300' }}
                                    {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-yellow-400 text-black border-4 ring-2 ring-black ring-inset' : '' }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-10 pt-6 border-t-2 border-black border-dashed space-y-4">
                        <div class="flex items-center text-[10px] font-black uppercase tracking-widest text-black">
                            <span class="w-4 h-4 bg-black mr-4 border border-black"></span> Answered
                        </div>
                        <div class="flex items-center text-[10px] font-black uppercase tracking-widest text-black">
                            <span class="w-4 h-4 bg-yellow-400 mr-4 border border-black"></span> Flagged
                        </div>
                        <div class="flex items-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                            <span class="w-4 h-4 bg-white mr-4 border border-black"></span> Pending
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-slate-100 border-2 border-black text-[9px] font-bold uppercase leading-relaxed italic text-slate-500">
                        Warning: Exam environment is being monitored. Manual page refreshes may result in session termination.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .question-container { animation: slideIn 0.2s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        #progress-bar { transition: width 0.4s cubic-bezier(0.65, 0, 0.35, 1); }
        
        /* Hide scrollbar for cleaner look */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: black; }
    </style>

    <script>
        // Progress Logic
        function updateProgress() {
            const answered = document.querySelectorAll('button[id^="nav-"].bg-black, button[id^="nav-"].bg-yellow-400').length;
            const total = {{ $exam->questions->count() }};
            const percent = (answered / total) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
        }

        // Timer Logic
        let duration = {{ $exam->duration_minutes }} * 60;
        const timerDisplay = document.getElementById('timer');
        const timerCont = document.getElementById('timer-container');

        const countdown = setInterval(() => {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            timerDisplay.innerHTML = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (duration < 300) { 
                timerCont.classList.remove('bg-black');
                timerCont.classList.add('bg-red-500');
                timerCont.classList.replace('shadow-[4px_4px_0px_0px_rgba(255,255,0,1)]', 'shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]');
            }

            if (duration <= 0) {
                clearInterval(countdown);
                document.getElementById('examForm').submit();
            }
            duration--;
        }, 1000);

        let currentQ = 0;
        const totalQ = {{ $exam->questions->count() }};

        function showQ(index) {
            document.querySelectorAll('.question-container').forEach(el => el.classList.add('hidden'));
            document.getElementById(`q-${index}`).classList.remove('hidden');
            
            document.querySelectorAll('button[id^="nav-"]').forEach(el => el.classList.remove('ring-4', 'ring-black', 'ring-offset-2'));
            document.getElementById(`nav-${index}`).classList.add('ring-4', 'ring-black', 'ring-offset-2');
            
            currentQ = index;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function nextQ(index) { if(index < totalQ - 1) showQ(index + 1); }
        function prevQ(index) { if(index > 0) showQ(index - 1); }

        function autoSave(qId, value, index) {
            if(!value) return;
            const data = { answers: { [qId]: value }, _token: '{{ csrf_token() }}' };
            fetch("{{ route('student.exams.auto-save', $exam->id) }}", {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            }).then(() => {
                const nav = document.getElementById(`nav-${index}`);
                if (!nav.classList.contains('bg-yellow-400')) {
                    nav.classList.add('bg-black', 'text-white');
                    nav.classList.remove('text-slate-300', 'bg-white');
                }
                updateProgress();
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
                    btn.classList.replace('bg-white', 'bg-yellow-400');
                    btn.classList.remove('shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]');
                    btn.classList.add('shadow-none', 'translate-x-1', 'translate-y-1');
                    btn.querySelector('span:last-child').innerText = 'Status: Flagged';
                    nav.classList.add('bg-yellow-400', 'text-black', 'border-4', 'ring-2', 'ring-black', 'ring-inset');
                    nav.classList.remove('bg-black', 'text-white');
                } else {
                    btn.classList.replace('bg-yellow-400', 'bg-white');
                    btn.classList.add('shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]');
                    btn.classList.remove('shadow-none', 'translate-x-1', 'translate-y-1');
                    btn.querySelector('span:last-child').innerText = 'Mark for Review';
                    nav.classList.remove('bg-yellow-400', 'text-black', 'border-4', 'ring-2', 'ring-black', 'ring-inset');
                    // Re-check if it's answered to set black bg
                    const q_el = document.getElementById(`q-${index}`);
                    const answered = q_el.querySelector('input:checked') || q_el.querySelector('textarea')?.value;
                    if(answered) nav.classList.add('bg-black', 'text-white');
                }
            });
        }

        showQ(0);
        updateProgress();
    </script>
</x-app-layout>