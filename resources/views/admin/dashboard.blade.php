<x-app-layout>
    <x-slot name="header">
        <div class="border-b-4 border-black pb-2">
            <h2 class="text-2xl font-black tracking-tighter text-black uppercase">Admin / Dashboard</h2>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f0f0] font-mono">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-0 mb-12 border-4 border-black bg-black">
                @php
                    $stats = [
                        ['label' => 'Total Exams', 'value' => $totalExams],
                        ['label' => 'Total Students', 'value' => $totalStudents],
                        ['label' => 'Ongoing Exams', 'value' => $ongoingExams],
                        ['label' => 'Submissions', 'value' => $latestSubmissions->count()],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="bg-white p-6 border-r-4 last:border-r-0 border-black">
                    <p class="text-xs font-bold text-black uppercase mb-2 underline italic">{{ $stat['label'] }}</p>
                    <p class="text-4xl font-black text-black">{{ $stat['value'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-12">
                    
                    <div class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <div class="flex items-center mb-8 border-b-2 border-black pb-2">
                            <h3 class="text-lg font-black uppercase text-black"> [ New_Entry ] </h3>
                        </div>
                        
                        <form action="{{ route('admin.exams.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-xs font-bold uppercase">Exam_Title:</label>
                                    <input type="text" name="title" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 text-sm p-2" placeholder="FINAL_ASSESSMENT" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold uppercase">Duration_Min:</label>
                                    <input type="number" name="duration_minutes" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 text-sm p-2" placeholder="60" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold uppercase">Start_Time:</label>
                                    <input type="datetime-local" name="start_time" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 text-sm p-2" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-bold uppercase">End_Time:</label>
                                    <input type="datetime-local" name="end_time" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 text-sm p-2" required>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold uppercase">Instructions:</label>
                                <textarea name="instructions" rows="3" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 text-sm p-2" placeholder="TYPE_HERE..."></textarea>
                            </div>
                            <div class="flex justify-start">
                                <button type="submit" class="px-8 py-3 text-sm font-black uppercase border-4 border-black bg-black text-white hover:bg-white hover:text-black transition-none active:translate-x-1 active:translate-y-1 shadow-none">
                                    SAVE_RECORD
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="border-4 border-black bg-white shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <div class="p-4 border-b-4 border-black bg-black">
                            <h3 class="text-sm font-bold uppercase text-white">Active_Exams_Log</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b-2 border-black bg-slate-100 text-xs font-bold">
                                        <th class="p-4 border-r-2 border-black uppercase">Title</th>
                                        <th class="p-4 border-r-2 border-black uppercase text-center">Schedule</th>
                                        <th class="p-4 uppercase text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y-2 divide-black">
                                    @foreach($exams as $exam)
                                    <tr class="hover:bg-yellow-50">
                                        <td class="p-4 border-r-2 border-black font-bold uppercase text-sm">
                                            {{ $exam->title }}
                                        </td>
                                        <td class="p-4 border-r-2 border-black text-center text-xs">
                                            {{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d / H:i') }}
                                        </td>
                                        <td class="p-4 text-right">
                                            <div class="flex justify-end space-x-4">
                                                <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-xs font-black uppercase underline hover:bg-black hover:text-white px-1">Manage</a>
                                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-xs font-black uppercase underline hover:bg-black hover:text-white px-1">Results</a>
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
                    <div class="border-4 border-black bg-white p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <h3 class="mb-6 text-sm font-black uppercase border-b-2 border-black inline-block">Latest_Activity</h3>
                        <div class="space-y-6">
                            @forelse($latestSubmissions as $sub)
                                <div class="border-b border-black last:border-0 pb-4">
                                    <div class="flex justify-between items-baseline">
                                        <p class="text-sm font-black uppercase">{{ $sub->user->name }}</p>
                                        <span class="text-[10px] font-bold bg-black text-white px-1 uppercase">{{ $sub->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-[11px] mt-1 text-slate-600 underline decoration-dotted">{{ $sub->exam->title }}</p>
                                    <p class="text-xs font-black mt-2">SCORE: {{ $sub->score }}%</p>
                                </div>
                            @empty
                                <p class="text-xs italic text-slate-500">NO_DATA_FOUND</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>