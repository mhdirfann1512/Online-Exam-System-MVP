<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <div class="flex items-center space-x-4">
                <div class="bg-slate-900 text-white px-3 py-1 rounded-lg text-sm font-bold tracking-tighter">EXAM</div>
                <h2 class="font-bold text-lg text-slate-800 tracking-tight leading-tight">{{ $exam->title }}</h2>
            </div>
            <div id="timer-container" class="flex items-center bg-white border border-slate-200 px-4 py-2 rounded-2xl shadow-sm">
                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div id="timer" class="text-slate-700 font-black text-xl tabular-nums tracking-tighter">--:--</div>
            </div>
        </div>
    </x-slot>

    <div class="w-full bg-slate-100 h-1.5">
        <div id="progress-bar" class="bg-slate-900 h-1.5 transition-all duration-500" style="width: 0%"></div>
    </div>

    <div class="py-10 bg-slate-50/50 min-h-screen" oncopy="return false" onpaste="return false" oncontextmenu="return false">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-8">
            
            <div class="lg:w-3/4">
                <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                    @csrf
                    @foreach($exam->questions as $index => $q)
                        <div class="question-container {{ $index == 0 ? '' : 'hidden' }} space-y-6" id="q-{{ $index }}" data-id="{{ $q->id }}">
                            
                            <div class="p-8 md:p-12 bg-white shadow-xl shadow-slate-200/50 rounded-3xl border border-slate-100 relative overflow-hidden">
                                <div class="flex justify-between items-center mb-10">
                                    <span class="px-4 py-1.5 bg-slate-50 text-slate-500 rounded-full text-xs font-bold tracking-widest uppercase">
                                        Question {{ $index + 1 }} of {{ $exam->questions->count() }}
                                    </span>
                                    
                                    <button type="button" onclick="toggleFlag({{ $q->id }}, {{ $index }})" 
                                            id="flag-btn-{{ $index }}" 
                                            class="flex items-center space-x-2 text-xs font-bold px-4 py-2 rounded-xl border transition-all duration-200 
                                            {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-400 border-slate-200 hover:border-slate-400' }}">
                                        <span>🚩</span>
                                        <span>{{ in_array($q->id, $submission->flagged_questions ?? []) ? 'Flagged' : 'Flag' }}</span>
                                    </button>
                                </div>
                                
                                <h3 class="text-2xl md:text-3xl mb-10 font-bold text-slate-800 leading-tight">
                                    {{ $q->question_text }}
                                </h3>

                                @if($q->type == 'mcq')
                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach($q->options as $key => $value)
                                            <label class="group relative flex items-center p-5 border-2 border-slate-100 rounded-2xl cursor-pointer transition-all hover:border-slate-900 hover:bg-slate-50">
                                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" 
                                                       class="w-5 h-5 text-slate-900 border-slate-300 focus:ring-slate-900"
                                                       {{ ($submission->answers[$q->id] ?? '') == $key ? 'checked' : '' }}
                                                       onchange="autoSave({{ $q->id }}, '{{ $key }}', {{ $index }})">
                                                <div class="ml-4 flex items-center">
                                                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 text-slate-600 font-black text-sm mr-3 group-hover:bg-slate-900 group-hover:text-white transition-colors uppercase">
                                                        {{ $key }}
                                                    </span>
                                                    <span class="text-lg text-slate-700 font-medium">{{ $value }}</span>
                                                </div>
                                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-slate-900 rounded-2xl pointer-events-none"></div>
                                            </label>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="relative">
                                        <textarea name="answers[{{ $q->id }}]" 
                                                  class="w-full rounded-2xl border-2 border-slate-100 focus:border-slate-900 focus:ring-0 p-6 text-lg text-slate-700 transition-all" 
                                                  rows="6" placeholder="Type your answer here..."
                                                  onblur="autoSave({{ $q->id }}, this.value, {{ $index }})">{{ $submission->answers[$q->id] ?? '' }}</textarea>
                                        <div class="absolute bottom-4 right-4 text-[10px] font-bold text-slate-300 uppercase tracking-widest">Auto-saving...</div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-between items-center pt-4">
                                <button type="button" onclick="prevQ({{ $index }})" 
                                        class="flex items-center px-8 py-4 text-slate-500 font-bold hover:text-slate-900 transition-colors {{ $index == 0 ? 'invisible' : '' }}">
                                    ← Previous
                                </button>
                                
                                @if($index + 1 == $exam->questions->count())
                                    <button type="submit" class="px-10 py-4 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 active:scale-95">
                                        Complete & Submit
                                    </button>
                                @else
                                    <button type="button" onclick="nextQ({{ $index }})" 
                                            class="px-12 py-4 bg-slate-900 text-white rounded-2xl font-black text-lg hover:bg-slate-800 transition-all shadow-xl shadow-slate-300 active:scale-95">
                                        Next Question →
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="lg:w-1/4">
                <div class="p-8 bg-white border border-slate-100 shadow-xl shadow-slate-200/50 rounded-3xl sticky top-8">
                    <h3 class="font-black text-slate-800 mb-6 uppercase tracking-widest text-xs">Jump to Question</h3>
                    <div class="grid grid-cols-4 sm:grid-cols-5 lg:grid-cols-4 gap-3">
                        @foreach($exam->questions as $index => $q)
                            <button type="button" id="nav-{{ $index }}" 
                                    onclick="showQ({{ $index }})"
                                    class="w-12 h-12 rounded-xl border-2 text-sm font-black flex items-center justify-center transition-all duration-200
                                    {{ ($submission->answers[$q->id] ?? '') ? 'bg-emerald-50 border-emerald-500 text-emerald-600' : 'bg-white text-slate-300 border-slate-100 hover:border-slate-300' }}
                                    {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'ring-4 ring-amber-200 border-amber-500' : '' }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-slate-50 space-y-3">
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="w-2 h-2 bg-emerald-500 mr-3 rounded-full"></span> Answered
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="w-2 h-2 bg-amber-500 mr-3 rounded-full"></span> Flagged
                        </div>
                        <div class="flex items-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            <span class="w-2 h-2 bg-slate-100 mr-3 rounded-full border border-slate-200"></span> Empty
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update Progress Bar
        function updateProgress() {
            const answered = document.querySelectorAll('button[id^="nav-"].bg-emerald-50').length;
            const total = {{ $exam->questions->count() }};
            const percent = (answered / total) * 100;
            document.getElementById('progress-bar').style.width = percent + '%';
        }

        // Logic Timer yang lebih cantik
        let duration = {{ $exam->duration_minutes }} * 60;
        const timerDisplay = document.getElementById('timer');
        const timerCont = document.getElementById('timer-container');

        const countdown = setInterval(() => {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            timerDisplay.innerHTML = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            
            if (duration < 300) { // Warn red if less than 5 mins
                timerCont.classList.add('border-red-200', 'bg-red-50');
                timerDisplay.classList.add('text-red-600');
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
            
            // Highlight current button
            document.querySelectorAll('button[id^="nav-"]').forEach(el => el.classList.remove('border-slate-900', 'scale-110'));
            document.getElementById(`nav-${index}`).classList.add('border-slate-900', 'scale-110');
            
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
                nav.classList.add('bg-emerald-50', 'border-emerald-500', 'text-emerald-600');
                nav.classList.remove('text-slate-300', 'border-slate-100');
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
                    btn.classList.replace('bg-white', 'bg-amber-500');
                    btn.classList.replace('text-slate-400', 'text-white');
                    btn.querySelector('span:last-child').innerText = 'Flagged';
                    nav.classList.add('ring-4', 'ring-amber-200', 'border-amber-500');
                } else {
                    btn.classList.replace('bg-amber-500', 'bg-white');
                    btn.classList.replace('text-white', 'text-slate-400');
                    btn.querySelector('span:last-child').innerText = 'Flag';
                    nav.classList.remove('ring-4', 'ring-amber-200', 'border-amber-500');
                }
            });
        }

        // Initialize first question highlight
        showQ(0);
        updateProgress();
    </script>
</x-app-layout>