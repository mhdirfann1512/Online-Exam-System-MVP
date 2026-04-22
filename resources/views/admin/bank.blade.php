<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Bank Soalan: Pilih untuk Exam {{ $exam->title ?? '' }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($allExams as $ex)
                <div class="mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                    <div class="p-4 bg-gray-50 flex justify-between items-center border-b">
                        <div>
                            <span class="font-bold text-lg text-indigo-700">{{ $ex->title }}</span>
                            <span class="ml-2 text-sm text-gray-500">({{ $ex->questions_count }} Soalan)</span>
                        </div>
                        
                        <div class="flex gap-2">
                            <form action="{{ route('admin.questions.copy_exam', $targetExamId) }}" method="POST">                                @csrf
                                <input type="hidden" name="source_exam_id" value="{{ $ex->id }}">
                                <button type="submit" class="bg-green-600 text-white px-4 py-1 rounded text-sm font-bold hover:bg-green-700">
                                    Salin Satu Set
                                </button>
                            </form>

                            <button onclick="toggleExam({{ $ex->id }})" class="bg-blue-600 text-white px-4 py-1 rounded text-sm font-bold">
                                Lihat & Pilih Soalan
                            </button>
                        </div>
                    </div>

                    <div id="exam-list-{{ $ex->id }}" class="hidden p-4 bg-white">
                        <form action="{{ route('admin.questions.copy_selected', $targetExamId) }}" method="POST">
                            @csrf
                            <table class="w-full text-sm">
                                @foreach($ex->questions as $q)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2 w-10">
                                        <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" class="rounded border-gray-300 text-indigo-600">
                                    </td>
                                    <td class="p-2">
                                        <span class="text-xs font-bold uppercase text-blue-600">[{{ $q->type }}]</span> 
                                        {{ $q->question_text }}
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <div class="mt-4 flex justify-end">
                                <button type="submit" class="bg-indigo-800 text-white px-6 py-2 rounded font-bold shadow hover:bg-black">
                                    Tambah Soalan Yang Dipilih
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function toggleExam(id) {
            const element = document.getElementById('exam-list-' + id);
            element.classList.toggle('hidden');
        }
    </script>
</x-app-layout>