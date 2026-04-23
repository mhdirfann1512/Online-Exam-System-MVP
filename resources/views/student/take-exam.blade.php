<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $exam->title }}</h2>
            <div id="timer" class="text-red-600 font-bold text-2xl">--:--</div>
        </div>
    </x-slot>

    <div class="py-12" oncopy="return false" onpaste="return false">

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
    
    <div class="md:w-3/4">
        <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
            @csrf
            @foreach($exam->questions as $index => $q)
                <div class="question-container {{ $index == 0 ? '' : 'hidden' }}" id="q-{{ $index }}" data-id="{{ $q->id }}">
                    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-gray-500">Soalan {{ $index + 1 }} dari {{ $exam->questions->count() }}</span>
                            <button type="button" onclick="toggleFlag({{ $q->id }}, {{ $index }})" 
                                    id="flag-btn-{{ $index }}" 
                                    class="text-sm px-3 py-1 rounded border {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'bg-yellow-500 text-white' : 'bg-gray-100' }}">
                                🚩 Flag for Review
                            </button>
                        </div>
                        
                        <p class="text-xl mb-6 font-medium">{{ $q->question_text }}</p>

                        @if($q->type == 'mcq')
                            <div class="space-y-3">
                                @foreach($q->options as $key => $value)
                                    <label class="block p-4 border rounded-xl hover:bg-indigo-50 cursor-pointer transition">
                                        <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" 
                                               {{ ($submission->answers[$q->id] ?? '') == $key ? 'checked' : '' }}
                                               onchange="autoSave({{ $q->id }}, '{{ $key }}', {{ $index }})">
                                        <span class="ml-2 font-bold">{{ $key }}.</span> {{ $value }}
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <textarea name="answers[{{ $q->id }}]" 
                                      class="w-full rounded-lg border-gray-300 p-4" 
                                      rows="5"
                                      onblur="autoSave({{ $q->id }}, this.value, {{ $index }})">{{ $submission->answers[$q->id] ?? '' }}</textarea>
                        @endif

                        <div class="flex justify-between mt-8">
                            <button type="button" onclick="prevQ({{ $index }})" class="px-6 py-2 bg-gray-200 rounded-lg {{ $index == 0 ? 'invisible' : '' }}">Back</button>
                            @if($index + 1 == $exam->questions->count())
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg font-bold">Finish & Submit</button>
                            @else
                                <button type="button" onclick="nextQ({{ $index }})" class="px-6 py-2 bg-indigo-600 text-white rounded-lg">Next</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </form>
    </div>

    <div class="md:w-1/4">
        <div class="p-6 bg-white shadow-sm sm:rounded-lg sticky top-6">
            <h3 class="font-bold mb-4">Question Navigator</h3>
            <div class="grid grid-cols-5 gap-2">
                @foreach($exam->questions as $index => $q)
                    <button type="button" id="nav-{{ $index }}" 
                            onclick="showQ({{ $index }})"
                            class="w-10 h-10 rounded-md border text-sm font-bold flex items-center justify-center transition
                            {{ ($submission->answers[$q->id] ?? '') ? 'bg-green-500 text-white border-green-600' : 'bg-white text-gray-700' }}
                            {{ in_array($q->id, $submission->flagged_questions ?? []) ? 'ring-4 ring-yellow-400' : '' }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
            <div class="mt-6 text-xs space-y-2">
                <div class="flex items-center"><span class="w-3 h-3 bg-green-500 mr-2 rounded"></span> Answered</div>
                <div class="flex items-center"><span class="w-3 h-3 border border-yellow-400 ring-2 ring-yellow-400 mr-2 rounded"></span> Flagged</div>
                <div class="flex items-center"><span class="w-3 h-3 bg-white border mr-2 rounded"></span> Unanswered</div>
            </div>
        </div>
    </div>
</div>
    
    </div>

    <script>
        // 1. Timer Logic
        let duration = {{ $exam->duration_minutes }} * 60;
        const timerDisplay = document.getElementById('timer');

        const countdown = setInterval(() => {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;
            timerDisplay.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
            
            if (duration <= 0) {
                clearInterval(countdown);
                alert("Time's up! Your exam will be submitted automatically.");
                document.getElementById('examForm').submit();
            }
            duration--;
        }, 1000);

        // 2. Anti-Cheat: Tab Switch
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                alert("WARNING: You switched tabs! This action is being logged.");
            }
        });


        let currentQ = 0;
const totalQ = {{ $exam->questions->count() }};

function showQ(index) {
    document.querySelectorAll('.question-container').forEach(el => el.classList.add('hidden'));
    document.getElementById(`q-${index}`).classList.remove('hidden');
    currentQ = index;
}

function nextQ(index) { if(index < totalQ - 1) showQ(index + 1); }
function prevQ(index) { if(index > 0) showQ(index - 1); }

// AJAX Auto-save
function autoSave(qId, value, index) {
    const data = { answers: { [qId]: value }, _token: '{{ csrf_token() }}' };
    
    fetch("{{ route('student.exams.auto-save', $exam->id) }}", {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    }).then(() => {
        // Tukar warna navigator jadi hijau
        document.getElementById(`nav-${index}`).classList.add('bg-green-500', 'text-white', 'border-green-600');
    });
}

// AJAX Toggle Flag
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
            btn.classList.replace('bg-gray-100', 'bg-yellow-500');
            btn.classList.add('text-white');
            nav.classList.add('ring-4', 'ring-yellow-400');
        } else {
            btn.classList.replace('bg-yellow-500', 'bg-gray-100');
            btn.classList.remove('text-white');
            nav.classList.remove('ring-4', 'ring-yellow-400');
        }
    });
}
    </script>
</x-app-layout>