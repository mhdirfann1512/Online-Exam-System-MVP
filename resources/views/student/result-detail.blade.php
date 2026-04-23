<x-app-layout>
    <div class="py-12 max-w-4xl mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-bold mb-4">Keputusan: {{ $exam->title }}</h2>
            <div class="mb-6 p-4 bg-indigo-50 rounded">
                <p class="text-lg">Skor Anda: <strong>{{ $submission->score }}%</strong> ({{ $submission->correct_answers }}/{{ $submission->total_questions }} Betul)</p>
            </div>

            <div class="space-y-6">
                @foreach($exam->questions as $index => $q)
                    @php
                        $studentAns = $submission->answers[$q->id] ?? 'Tiada Jawapan';
                        $isCorrect = false;
                        
                        // Logic check ringkas untuk UI
                        if($q->type == 'mcq') {
                            $isCorrect = strtoupper($studentAns) == strtoupper($q->correct_answer);
                        } else {
                            $isCorrect = str_contains(strtolower($studentAns), strtolower(explode(',', $q->correct_answer)[0]));
                        }
                    @endphp

                    <div class="p-4 border rounded {{ $isCorrect ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                        <p class="font-bold">{{ $index + 1 }}. {{ $q->question_text }}</p>
                        
                        <div class="mt-2 text-sm">
                            <p>Jawapan Anda: <span class="{{ $isCorrect ? 'text-green-700' : 'text-red-700' }} font-bold">{{ $studentAns }}</span></p>
                            
                            @if(!$isCorrect)
                                <p class="text-gray-600 mt-1">Jawapan Sebenar: <span class="font-bold">{{ $q->correct_answer }}</span></p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                <a href="{{ route('student.dashboard') }}" class="text-indigo-600 underline">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>