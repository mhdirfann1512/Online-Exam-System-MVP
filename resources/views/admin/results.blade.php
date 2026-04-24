<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 max-w-7xl mx-auto">
            <div>
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">Examination Results</p>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-tight">
                    {{ $exam->title }}
                </h2>
            </div>
            
            <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg {{ $exam->is_published ? 'bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white shadow-rose-100' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-emerald-200' }}">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($exam->is_published)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        @endif
                    </svg>
                    {{ $exam->is_published ? 'Tutup Keputusan' : 'Terbitkan Keputusan' }}
                </button>
            </form>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase">Total Submissions</p>
                        <p class="text-2xl font-black text-slate-800">{{ $submissions->count() }}</p>
                    </div>
                </div>
                </div>

            <div class="bg-white overflow-hidden shadow-xl shadow-slate-200/60 rounded-[2.5rem] border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Student Information</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Correct Answers (Manual Edit)</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Final Score</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Completion Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($submissions as $s)
                            <tr class="group hover:bg-slate-50/50 transition-all">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold text-xs uppercase">
                                            {{ substr($s->user->name, 0, 2) }}
                                        </div>
                                        <p class="font-bold text-slate-800 tracking-tight">{{ $s->user->name }}</p>
                                    </div>
                                </td>
                                <td class="p-6">
                                    <form action="{{ route('admin.submissions.update-score', $s->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                        @csrf
                                        <div class="relative">
                                            <input type="number" name="new_correct" value="{{ $s->correct_answers }}" 
                                                class="w-20 pl-4 pr-8 py-2 bg-slate-50 border-none rounded-xl font-black text-slate-700 focus:ring-2 focus:ring-indigo-500 transition-all"
                                                min="0" max="{{ $s->total_questions }}">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-300">/{{ $s->total_questions }}</span>
                                        </div>
                                        <button type="submit" onclick="return confirm('Update markah untuk {{ $s->user->name }}?')" 
                                            class="p-2.5 bg-white border border-slate-200 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                </td>
                                <td class="p-6 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xl font-black {{ $s->score >= 50 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $s->score }}%
                                        </span>
                                        <span class="text-[9px] font-black uppercase tracking-widest {{ $s->score >= 50 ? 'text-emerald-400' : 'text-rose-400' }}">
                                            {{ $s->score >= 50 ? 'Passed' : 'Failed' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-6 text-right">
                                    <p class="text-sm font-bold text-slate-500">{{ $s->created_at->format('d M Y') }}</p>
                                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-tighter">{{ $s->created_at->format('h:i A') }}</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 flex justify-start">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-xs font-black text-slate-400 uppercase tracking-widest hover:text-indigo-600 transition-colors group">
                    <svg class="w-4 h-4 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Master Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>