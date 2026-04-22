<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Admin Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="mb-4 text-lg font-bold">Create New Exam</h3>
                <form action="{{ route('admin.exams.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="title" placeholder="Exam Title" class="rounded border-gray-300" required>
                        <input type="number" name="duration_minutes" placeholder="Duration (Mins)" class="rounded border-gray-300" required>
                        <input type="datetime-local" name="start_time" class="rounded border-gray-300" required>
                        <input type="datetime-local" name="end_time" class="rounded border-gray-300" required>
                    </div>
                    <textarea name="instructions" placeholder="Instructions" class="w-full mt-4 rounded border-gray-300"></textarea>
                    <button type="submit" class="px-4 py-2 mt-4 text-black bg-black rounded">Create Exam</button>
                </form>
            </div>

            <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="mb-4 text-lg font-bold">Existing Exams</h3>
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-2 border">Title</th>
                            <th class="p-2 border">Start</th>
                            <th class="p-2 border">Action</th>
                            <th class="p-2 border">Download Question</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr>
                            <td class="p-2 border">{{ $exam->title }}</td>
                            <td class="p-2 border">{{ $exam->start_time }}</td>
                            <td class="p-2 border text-center">
                                <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-blue-500 underline">Add Questions</a>
                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="ml-4 text-green-600 underline font-bold">View Results</a>
                            </td>
                            <td class="p-2 border text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.exams.export-excel', $exam->id) }}" 
                                    class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1 px-2 rounded flex items-center shadow-sm transition"
                                    title="Download Excel">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Excel
                                    </a>

                                    <a href="{{ route('admin.exams.export-pdf', $exam->id) }}" 
                                    class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1 px-2 rounded flex items-center shadow-sm transition"
                                    title="Download PDF">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>