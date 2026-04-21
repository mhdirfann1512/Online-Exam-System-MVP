<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Manage Questions: {{ $exam->title }}</h2>
    </x-slot>

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