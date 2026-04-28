<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center bg-white p-4 border-b border-black">
            <h2 class="font-bold text-sm text-black uppercase tracking-widest">
                SESI_MENJAWAB: {{ $exam->title }}
            </h2>
            <div class="flex items-center gap-4 border-l border-black pl-4">
                <span class="text-[10px] text-gray-400 font-bold uppercase">Masa_Berbaki:</span>
                <div id="timer" class="text-black font-bold text-2xl tracking-tighter leading-none">--:--</div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-white min-h-screen" oncopy="return false" onpaste="return false">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">
            
            <div class="md:w-3/4 bg-white border border-black p-8 relative">
                <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                    @csrf
                    @foreach($exam->questions as $index => $q)
                        <div class="question-container {{ $index == 0 ? '' : 'hidden' }}" id="q-{{ $index }}" data-id="{{ $q->id }}">
                            
                            <div class="flex justify-between items-start mb-10 border-b border-black pb-4 border-dotted">
                                <div>
                                    <span class="text-[10px] font-bold uppercase text-gray-400 tracking-[0.2em]">Nombor_Soalan</span>
                                    <h3 class="text-lg font-bold uppercase tracking-tight mt-1">SOALAN {{ $index + 1 }} / {{ $exam->questions->count() }}</h3>
                                </div>
                                <button type="button" onclick="toggleFlag({{ $q->id }}, {{ $index }})" 
                                        id="flag-btn-{{ $index }}" 
                                        class="text-[9px] font-bold uppercase px-3 py-1 border border-black transition-all {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-black text-white' : 'bg-white text-black hover:bg-black hover:text-white' }}">
                                    [ TANDA_SEMAKAN ]
                                </button>
                            </div>
                            
                            <div class="mb-12">
                                <p class="text-md font-bold leading-relaxed text-black uppercase tracking-tight">{{ $q->question_text }}</p>
                            </div>

                            @if($q->type == 'mcq')
                                <div class="space-y-3">
                                    @foreach($q->options as $key => $value)
                                        <label class="group flex items-center p-3 border border-black cursor-pointer hover:bg-gray-50 transition-all">
                                            <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" 
                                                   class="w-4 h-4 border border-black text-black focus:ring-0 rounded-none"
                                                   {{ ($submission->answers[$q->id] ?? '') == $key ? 'checked' : '' }}
                                                   onchange="autoSave({{ $q->id }}, '{{ $key }}', {{ $index }})">
                                            <div class="ml-4 flex gap-2 text-xs uppercase font-bold tracking-tight text-black">
                                                <span class="text-gray-400">{{ $key }}.</span> 
                                                <span>{{ $value }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="border border-black p-1">
                                    <textarea name="answers[{{ $q->id }}]" 
                                              class="w-full border-0 p-4 text-xs font-bold uppercase bg-gray-50 focus:ring-0 focus:bg-white transition-colors rounded-none" 
                                              placeholder="SILA INPUT JAWAPAN ANDA DI SINI..."
                                              rows="8"
                                              onblur="autoSave({{ $q->id }}, this.value, {{ $index }})">{{ $submission->answers[$q->id] ?? '' }}</textarea>
                                </div>
                            @endif

                            <div class="flex justify-between mt-12 pt-6 border-t border-black">
                                <button type="button" onclick="prevQ({{ $index }})" 
                                        class="text-[10px] font-bold uppercase underline underline-offset-4 {{ $index == 0 ? 'invisible' : '' }}">
                                    << KEMBALI
                                </button>
                                
                                @if($index + 1 == $exam->questions->count())
                                    <div class="flex flex-col items-end gap-2">
                                        @error('answers')
                                            <span class="text-[10px] font-bold text-red-600 uppercase tracking-tighter">
                                                {{ $message }}
                                            </span>
                                        @enderror

                                        <button type="submit" class="text-[10px] font-bold uppercase px-8 py-2 border border-black hover:bg-black hover:text-white transition-all">
                                            [ HANTAR_JAWAPAN ]
                                        </button>
                                    </div>
                                @else
                                    <button type="button" onclick="nextQ({{ $index }})" 
                                            class="text-[10px] font-bold uppercase underline underline-offset-4">
                                        SOALAN_SETERUSNYA >>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </form>
            </div>

            <div class="md:w-1/4">
                <div class="p-6 border border-black bg-white h-full">
                    <h3 class="text-[10px] font-bold uppercase tracking-widest mb-6 border-b border-black pb-2 text-center text-gray-400">Halaman</h3>
                    
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($exam->questions as $index => $q)
                            <button type="button" id="nav-{{ $index }}" 
                                    onclick="showQ({{ $index }})"
                                    class="w-full aspect-square border border-black text-[10px] font-bold flex items-center justify-center transition-all rounded-none
                                    {{ ($submission->answers[$q->id] ?? '') ? 'bg-black text-white' : 'bg-white text-black' }}
                                    {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'border-dashed' : '' }}">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-10 space-y-3 pt-6 border-t border-black border-dotted">
                        <div class="flex items-center text-[9px] font-bold uppercase">
                            <span class="w-3 h-3 bg-black mr-3 border border-black"></span> DIJAWAB
                        </div>
                        <div class="flex items-center text-[9px] font-bold uppercase">
                            <span class="w-3 h-3 bg-white mr-3 border border-black border-dashed"></span> SEMAKAN
                        </div>
                        <div class="flex items-center text-[9px] font-bold uppercase">
                            <span class="w-3 h-3 bg-white mr-3 border border-black"></span> BELUM_JAWAB
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. Timer Logic (Updated with LocalStorage)
        const examId = {{ $exam->id }};
        const storageKey = `exam_timer_${examId}`;

        // Ambil masa dari LocalStorage kalau ada, kalau tak guna duration asal
        let duration;
        const savedTime = localStorage.getItem(storageKey);

        if (savedTime) {
            duration = parseInt(savedTime);
        } else {
            duration = {{ $exam->duration_minutes }} * 60;
        }

        const timerDisplay = document.getElementById('timer');

        const countdown = setInterval(() => {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            timerDisplay.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            // Simpan baki masa setiap saat
            localStorage.setItem(storageKey, duration);
            
            if (duration <= 0) {
                clearInterval(countdown);
                localStorage.removeItem(storageKey); // Padam stor sebab masa dah habis
                alert("MASA TAMAT! Jawapan anda akan dihantar secara automatik.");
                document.getElementById('examForm').submit();
            }
            duration--;
        }, 1000);

        // Tambah ini dalam script kau
        document.getElementById('examForm').onsubmit = function() {
            // Padam timer bila student tekan submit dan validation lepas
            // Tapi kalau validation fail, page refresh, timer akan ambil dari localStorage (so masa tak reset)
            // Cuma masa success page nanti kita kena clear betul-betul.
        };

        // 2. Anti-Cheat (No styling change needed)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                console.log("LOG: Pelajar menukar tab.");
            }
        });

        // --- ANTI-TAB SWITCH LOGIC ---
        let tabSwitchCount = localStorage.getItem(`tab_switch_${examId}`) || 0;

        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Ini waktu dia tinggalkan tab (buka Google/Tab lain)
                console.log("LOG: Pelajar keluar dari tab exam.");
            } else {
                // Ini waktu dia masuk balik ke tab exam
                tabSwitchCount++;
                localStorage.setItem(`tab_switch_${examId}`, tabSwitchCount);
                
                // Keluar amaran
                alert(`AMARAN KERAS: Anda telah menukar tab sebanyak ${tabSwitchCount} kali!\n\nSegala aktiviti anda direkodkan untuk semakan pihak pengawas.`);
                
                // Kalau kau nak lagi cuak, boleh tukar warna border page jadi merah ke apa
                document.body.style.border = "10px solid red";
                setTimeout(() => {
                    document.body.style.border = "none";
                }, 2000);
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
                nav.classList.remove('bg-white', 'text-black');
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
                    btn.classList.add('bg-black', 'text-white');
                    nav.classList.add('border-dashed');
                } else {
                    btn.classList.remove('bg-black', 'text-white');
                    nav.classList.remove('border-dashed');
                }
            });
        }
    </script>
</x-app-layout>