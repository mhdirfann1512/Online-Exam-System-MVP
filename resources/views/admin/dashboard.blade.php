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
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>