<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800">{{ $exam->title }}</h2>
            <div id="timer" class="text-red-600 font-bold text-2xl">--:--</div>
        </div>
    </x-slot>

    <div class="py-12" oncopy="return false" onpaste="return false">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form id="examForm" action="{{ route('student.exams.submit', $exam->id) }}" method="POST">
                @csrf
                @foreach($exam->questions as $index => $q)
                    <div class="mb-8 p-6 bg-white shadow-sm sm:rounded-lg">
                        <p class="font-bold text-lg">Question {{ $index + 1 }}</p>
                        <p class="mb-4">{{ $q->question_text }}</p>

                        @if($q->type == 'mcq')
                            @foreach($q->options as $key => $value)
                                <label class="block mb-2 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="answers[{{ $q->id }}]" value="{{ $key }}" required>
                                    {{ $key }}: {{ $value }}
                                </label>
                            @endforeach
                        @else
                            <textarea name="answers[{{ $q->id }}]" class="w-full rounded border-gray-300" placeholder="Type your answer here..."></textarea>
                        @endif
                    </div>
                @endforeach

                <button type="submit" class="w-full bg-green-600 text-white py-4 rounded-lg font-bold text-xl hover:bg-green-700">
                    Submit Exam
                </button>
            </form>
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
    </script>
</x-app-layout>