<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Manage Questions: {{ $exam->title }}</h2>
    </x-slot>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="mb-4 font-bold">Add New Question</h3>
                
                <form action="{{ route('admin.questions.store', $exam->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label>Type:</label>
                        <select name="type" id="type-select" class="rounded border-gray-300" onchange="toggleFields()">
                            <option value="mcq">MCQ</option>
                            <option value="subjective">Subjective</option>
                        </select>
                    </div>

                    <textarea name="question_text" placeholder="Your Question Here" class="w-full rounded border-gray-300" required></textarea>

                    <div id="mcq-fields" class="mt-4 grid grid-cols-2 gap-2">
                        <input type="text" name="option_a" placeholder="Option A" class="rounded border-gray-300">
                        <input type="text" name="option_b" placeholder="Option B" class="rounded border-gray-300">
                        <input type="text" name="option_c" placeholder="Option C" class="rounded border-gray-300">
                        <input type="text" name="option_d" placeholder="Option D" class="rounded border-gray-300">
                        <select name="correct_answer" class="rounded border-gray-300 mt-2">
                            <option value="A">Correct: A</option>
                            <option value="B">Correct: B</option>
                            <option value="C">Correct: C</option>
                            <option value="D">Correct: D</option>
                        </select>
                    </div>

                    <div id="subjective-fields" class="mt-4 hidden">
                        <input type="text" name="correct_answer_subjective" placeholder="Keywords (separate by comma: merdeka,1957,tunku)" class="w-full rounded border-gray-300">
                    </div>

                    <button type="submit" class="mt-4 px-4 py-2 bg-green-600 text-white rounded">Save Question</button>
                </form>

                <div class="mb-6">
<a href="{{ route('admin.questions.bank', $exam->id) }}" 
   class="inline-flex items-center px-6 py-3 bg-indigo-700 text-white rounded-lg font-bold shadow hover:bg-indigo-900 transition text-sm">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
    Ambil dari Bank Soalan
</a>
</div>

                <div class="p-6 mt-6 bg-gray-50 border-2 border-dashed rounded-lg">
                    <h3 class="font-bold mb-2 text-gray-700">Bulk Upload (CSV/Excel)</h3>
                    
                    <form action="{{ route('admin.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col">
                            <input type="file" name="file" class="mb-4 p-2 bg-white border rounded" required>
                            <button type="submit" class="w-fit bg-gray-800 text-white px-6 py-2 rounded hover:bg-black transition">
                                Upload Now
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-4 p-3 bg-blue-50 rounded">
                        <p class="text-xs text-blue-700">
                            <strong>Format CSV:</strong><br>
                            type, question_text, option_a, option_b, option_c, option_d, correct_answer
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-bold mb-4 text-lg">Senarai Soalan Bagi Exam Ini</h3>
                    @foreach($questions as $q)
                    <div class="p-4 mb-4 bg-white border rounded shadow-sm">
                        <div class="flex justify-between">
                            <span class="text-xs font-bold uppercase p-1 bg-blue-100 text-blue-700 rounded">{{ $q->type }}</span>
                            <span class="text-xs text-gray-400">ID: {{ $q->id }}</span>
                        </div>
                        <p class="mt-2 font-medium">{{ $q->question_text }}</p>
                        
                        @if($q->type == 'mcq')
                            <ul class="text-sm text-gray-600 ml-4 mt-2 list-disc">
                                <li>A: {{ $q->options['A'] ?? '-' }}</li>
                                <li>B: {{ $q->options['B'] ?? '-' }}</li>
                                <li>C: {{ $q->options['C'] ?? '-' }}</li>
                                <li>D: {{ $q->options['D'] ?? '-' }}</li>
                            </ul>
                        @endif
                        
                        <div class="mt-3 pt-2 border-t border-dashed">
                            <p class="text-sm font-bold text-green-600">Jawapan Betul/Keywords: {{ $q->correct_answer }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('type-select').value;
            document.getElementById('mcq-fields').classList.toggle('hidden', type !== 'mcq');
            document.getElementById('subjective-fields').classList.toggle('hidden', type !== 'subjective');
            
            // Tukar name attribute supaya controller tak pening
            if(type === 'subjective') {
                document.getElementsByName('correct_answer_subjective')[0].setAttribute('name', 'correct_answer');
            }
        }
    </script>
</x-app-layout>