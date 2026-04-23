<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Admin Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Exams</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalExams }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-green-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Students</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Ongoing Exams</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $ongoingExams }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 uppercase">Latest Submissions</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $latestSubmissions->count() }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
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
                            <button type="submit" class="px-6 py-2 mt-4 text-white bg-indigo-600 rounded-lg font-bold hover:bg-indigo-700 transition">Create Exam</button>
                        </form>
                    </div>

                    <div class="p-6 bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                        <h3 class="mb-4 text-lg font-bold">Existing Exams</h3>
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="p-3 font-semibold text-sm">Title</th>
                                    <th class="p-3 font-semibold text-sm">Start Time</th>
                                    <th class="p-3 font-semibold text-sm text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($exams as $exam)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 text-sm">{{ $exam->title }}</td>
                                    <td class="p-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M, h:i A') }}</td>
                                    <td class="p-3 text-center space-x-2">
                                        <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-blue-600 hover:underline text-sm font-medium">Manage Questions</a>
                                        <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-green-600 hover:underline text-sm font-bold">View Results</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="p-6 bg-white shadow-sm sm:rounded-lg">
                        <h3 class="mb-4 text-lg font-bold">Latest Submissions</h3>
                        <div class="space-y-4">
                            @forelse($latestSubmissions as $sub)
                                <div class="p-3 bg-gray-50 rounded-lg border">
                                    <p class="text-sm font-bold text-gray-800">{{ $sub->user->name }}</p>
                                    <p class="text-xs text-gray-500">Exam: {{ $sub->exam->title }}</p>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-xs font-semibold px-2 py-1 bg-indigo-100 text-indigo-700 rounded">Score: {{ $sub->score }}%</span>
                                        <span class="text-xs text-gray-400 self-center">{{ $sub->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 italic text-center">No submissions yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>