<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto border-b-4 border-black pb-4">
            <p class="text-[10px] font-bold text-black uppercase tracking-[0.3em] mb-1 font-mono">// EXAM_FINAL_REPORT</p>
            <h2 class="text-3xl font-black text-black tracking-tighter uppercase font-mono">
                {{ $exam->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-[#f0f0f0] min-h-screen font-mono text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="inline-block bg-white p-6 border-4 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <p class="text-[10px] font-black uppercase mb-1 underline">Total_Submissions:</p>
                <p class="text-4xl font-black italic">{{ $submissions->count() }}</p>
            </div>

            <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black text-white border-b-4 border-black">
                                <th class="p-4 text-xs font-black uppercase tracking-widest">Student_Identity</th>
                                <th class="p-4 text-xs font-black uppercase tracking-widest text-center">Score_Adjustment</th>
                                <th class="p-4 text-xs font-black uppercase tracking-widest text-center">Final_Grade</th>
                                <th class="p-4 text-xs font-black uppercase tracking-widest text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-black">
                            @foreach($submissions as $s)
                            <tr class="hover:bg-yellow-50 transition-none">
                                <td class="p-4 border-r-2 border-black">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 bg-black text-white flex items-center justify-center font-black text-xs">
                                            {{ substr($s->user->name, 0, 2) }}
                                        </div>
                                        <p class="font-black uppercase text-sm">{{ $s->user->name }}</p>
                                    </div>
                                </td>
                                <td class="p-4 border-r-2 border-black">
                                    <form action="{{ route('admin.submissions.update-score', $s->id) }}" method="POST" class="flex items-center justify-center gap-0">
                                        @csrf
                                        <input type="number" name="new_correct" value="{{ $s->correct_answers }}" 
                                            class="w-16 border-2 border-black font-black text-center text-sm focus:ring-0 focus:bg-yellow-100"
                                            min="0" max="{{ $s->total_questions }}">
                                        <span class="px-2 font-bold text-xs">/{{ $s->total_questions }}</span>
                                        <button type="submit" onclick="return confirm('Update markah?')" 
                                            class="ml-2 p-2 bg-black text-white border-2 border-black hover:bg-white hover:text-black transition-none">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-4 border-r-2 border-black text-center">
                                    <div class="inline-block p-1 border-2 border-black {{ $s->score >= 50 ? 'bg-green-400' : 'bg-red-400' }}">
                                        <span class="text-lg font-black block leading-none">{{ $s->score }}%</span>
                                        <span class="text-[9px] font-black uppercase tracking-tighter italic border-t border-black">
                                            {{ $s->score >= 50 ? 'PASSED' : 'FAILED' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 text-right">
                                    <p class="text-xs font-black uppercase">{{ $s->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[10px] font-bold text-slate-500 italic">{{ $s->created_at->format('H:i:s') }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-10 border-t-4 border-black border-dashed">
                
                <a href="{{ route('admin.dashboard') }}" class="text-xs font-black uppercase underline decoration-4 hover:bg-black hover:text-white px-2 py-1 transition-none">
                    << RETURN_TO_DASHBOARD
                </a>

                <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="group relative px-10 py-4 border-4 border-black font-black uppercase tracking-[0.2em] text-sm transition-none active:translate-x-1 active:translate-y-1 {{ $exam->is_published ? 'bg-red-500 text-white' : 'bg-green-500 text-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)]' }}">
                        @if($exam->is_published)
                            [ TERMINATE_PUBLICATION ]
                        @else
                            [ EXECUTE_PUBLISH_RESULTS ]
                        @endif
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>