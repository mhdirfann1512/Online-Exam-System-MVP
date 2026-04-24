<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-medium tracking-tight text-slate-800">Admin Dashboard</h2>
    </x-slot>

    <div class="py-12 bg-slate-50/50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                @php
                    $stats = [
                        ['label' => 'Total Exams', 'value' => $totalExams, 'color' => 'text-blue-600'],
                        ['label' => 'Total Students', 'value' => $totalStudents, 'color' => 'text-emerald-600'],
                        ['label' => 'Ongoing Exams', 'value' => $ongoingExams, 'color' => 'text-amber-600'],
                        ['label' => 'Submissions', 'value' => $latestSubmissions->count(), 'color' => 'text-indigo-600'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 transition-all hover:shadow-md">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">{{ $stat['label'] }}</p>
                    <p class="text-3xl font-bold {{ $stat['color'] }}">{{ $stat['value'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    
                    <div class="p-8 bg-white border border-slate-100 rounded-2xl shadow-sm">
                        <div class="flex items-center mb-6">
                            <div class="w-1 h-6 bg-slate-900 rounded-full mr-3"></div>
                            <h3 class="text-lg font-bold text-slate-800">Create New Exam</h3>
                        </div>
                        
                        <form action="{{ route('admin.exams.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1 ml-1">Exam Title</label>
                                    <input type="text" name="title" class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-sm transition-all" placeholder="Final Year Assessment" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1 ml-1">Duration (Minutes)</label>
                                    <input type="number" name="duration_minutes" class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-sm" placeholder="60" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1 ml-1">Start Time</label>
                                    <input type="datetime-local" name="start_time" class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1 ml-1">End Time</label>
                                    <input type="datetime-local" name="end_time" class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-sm" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1 ml-1">Instructions</label>
                                <textarea name="instructions" rows="3" class="w-full rounded-xl border-slate-200 focus:border-slate-900 focus:ring-slate-900 text-sm" placeholder="Read carefully..."></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-8 py-3 text-sm font-bold text-white bg-slate-900 rounded-xl hover:bg-slate-800 transition-all active:scale-95 shadow-lg shadow-slate-200">
                                    Create Exam
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800">Existing Exams</h3>
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-full">{{ $exams->count() }} Total</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50/50">
                                    <tr>
                                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Title</th>
                                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Start Time</th>
                                        <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($exams as $exam)
                                    <tr class="hover:bg-slate-50/80 transition-colors">
                                        <td class="p-4">
                                            <p class="text-sm font-bold text-slate-700">{{ $exam->title }}</p>
                                        </td>
                                        <td class="p-4 text-center">
                                            <span class="text-xs font-medium text-slate-500 bg-slate-100 px-2 py-1 rounded">
                                                {{ \Carbon\Carbon::parse($exam->start_time)->format('d M, h:i A') }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end space-x-3">
                                                <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition">Manage</a>
                                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 transition">Results</a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-white border border-slate-100 rounded-2xl shadow-sm">
                        <h3 class="mb-6 text-lg font-bold text-slate-800">Latest Submissions</h3>
                        <div class="space-y-4">
                            @forelse($latestSubmissions as $sub)
                                <div class="group p-4 bg-slate-50/50 rounded-xl border border-transparent hover:border-slate-200 transition-all">
                                    <div class="flex justify-between items-start mb-1">
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-indigo-600 transition-colors">{{ $sub->user->name }}</p>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $sub->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mb-3 italic">Exam: {{ $sub->exam->title }}</p>
                                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-white border border-slate-200 text-slate-700">
                                        Score: {{ $sub->score }}%
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8">
                                    <p class="text-sm text-slate-400 italic">No submissions yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>