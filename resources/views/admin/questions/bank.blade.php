<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Bank Soalan Pusat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="p-3 border">Soalan</th>
                            <th class="p-3 border">Jenis</th>
                            <th class="p-3 border">Asal Exam</th>
                            <th class="p-3 border text-center">Tindakan (Copy ke Exam)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $q)
                        <tr>
                            <td class="p-3 border text-sm">{{ $q->question_text }}</td>
                            <td class="p-3 border text-xs uppercase font-bold">{{ $q->type }}</td>
                            <td class="p-3 border text-sm text-gray-500">{{ $q->exam->title ?? 'N/A' }}</td>
                            <td class="p-3 border">
                                <form action="{{ route('admin.questions.copy', $q->id) }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <select name="target_exam_id" required class="text-xs rounded border-gray-300 w-full">
                                        <option value="">Pilih Exam Target...</option>
                                        @foreach($allExams as $ex)
                                            <option value="{{ $ex->id }}">{{ $ex->title }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                        Add
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>