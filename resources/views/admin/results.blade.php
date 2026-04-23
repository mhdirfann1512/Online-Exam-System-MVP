<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Results: {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border text-left">Student Name</th>
                            <th class="p-3 border text-left">Score</th>
                            <th class="p-3 border text-center">Percentage</th>
                            <th class="p-3 border text-center">Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $s)
                        <tr>
                            <td class="p-3 border">{{ $s->user->name }}</td>
                            <td class="p-4 border-b text-center">
                                <form action="{{ route('admin.submissions.update-score', $s->id) }}" method="POST" class="flex items-center justify-center space-x-2">
                                    @csrf
                                    <input type="number" 
                                        name="new_correct" 
                                        value="{{ $s->correct_answers }}" 
                                        class="w-16 p-1 text-center border rounded-md focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                        min="0" 
                                        max="{{ $s->total_questions }}">
                                    
                                    <span class="text-gray-500 font-mono">/ {{ $s->total_questions }}</span>
                                    
                                    <button type="submit" onclick="return confirm('Kemaskini markah pelajar ini?')" class="bg-yellow-500 hover:bg-yellow-600 text-white p-1 rounded shadow-sm transition" title="Update Markah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td class="p-3 border text-center font-bold {{ $s->score >= 50 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $s->score }}%
                            </td>
                            <td class="p-3 border text-center text-sm">
                                {{ $s->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST">
    @csrf
    <button type="submit" class="{{ $exam->is_published ? 'bg-red-500' : 'bg-green-600' }} text-white px-4 py-2 rounded shadow">
        {{ $exam->is_published ? 'Tutup Keputusan' : 'Terbitkan Keputusan' }}
    </button>
</form>
                <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-block text-blue-600">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>