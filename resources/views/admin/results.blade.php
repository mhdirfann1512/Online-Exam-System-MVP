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
                            <td class="p-4 border-b text-center font-mono">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-md border border-blue-200">
                                    {{ $s->correct_answers }} / {{ $s->total_questions }}
                                </span>
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
                <a href="{{ route('admin.dashboard') }}" class="mt-4 inline-block text-blue-600">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</x-app-layout>