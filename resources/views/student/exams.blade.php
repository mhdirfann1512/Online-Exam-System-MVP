<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto border-b-4 border-black pb-4">
            <p class="text-[10px] font-bold text-black uppercase tracking-[0.3em] mb-1 font-mono">// STUDENT_EXAM_PORTAL</p>
            <h2 class="text-3xl font-black text-black tracking-tighter uppercase font-mono">
                {{ __('Senarai Peperiksaan') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8 font-mono">
        @if(session('success'))
            <div class="bg-emerald-400 border-4 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center mb-4">
                <svg class="w-6 h-6 mr-3 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                <span class="text-xs font-black uppercase tracking-tight">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500 border-4 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center mb-4 text-white">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-black uppercase tracking-tight">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <div class="py-10 bg-[#e5e5e5] min-h-screen font-mono text-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @php
                    $studentStats = [
                        ['label' => 'Upcoming_Seq', 'value' => $upcomingExams, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-yellow-400'],
                        ['label' => 'Completed_Log', 'value' => $completedExams, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-400'],
                        ['label' => 'Avg_Efficiency', 'value' => number_format($averageScore, 1) . '%', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'bg' => 'bg-blue-400'],
                    ];
                @endphp

                @foreach($studentStats as $stat)
                <div class="bg-white p-6 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] flex items-center transition-none">
                    <div class="p-3 border-2 border-black {{ $stat['bg'] }}">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-black text-black leading-none">{{ $stat['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center mb-8 border-l-8 border-black pl-4">
                <h3 class="text-xl font-black text-black uppercase tracking-tighter">Available_Tests.exe</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($exams as $exam)
                    <div class="flex flex-col bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] group transition-none">
                        
                        <div class="p-6 bg-black text-white">
                            <div class="flex justify-between items-start mb-2">
                                <span class="px-2 py-0.5 bg-yellow-400 text-black text-[9px] font-black uppercase border border-black">
                                    {{ $exam->duration_minutes }} MIN_LIMIT
                                </span>
                            </div>
                            <h4 class="text-xl font-black uppercase tracking-tighter leading-tight group-hover:text-yellow-400 transition-none">
                                {{ $exam->title }}
                            </h4>
                        </div>

                        <div class="px-6 py-5 space-y-3 bg-white border-b-4 border-black">
                            <div class="flex items-center text-[11px] font-bold uppercase tracking-tight">
                                <span class="w-16 text-slate-400">START:</span>
                                <span class="bg-slate-100 px-2 py-1">{{ \Carbon\Carbon::parse($exam->start_time)->format('d.M.Y // H:i') }}</span>
                            </div>
                            <div class="flex items-center text-[11px] font-bold uppercase tracking-tight">
                                <span class="w-16 text-red-500">END:</span>
                                <span class="bg-red-50 text-red-600 px-2 py-1">{{ \Carbon\Carbon::parse($exam->end_time)->format('d.M.Y // H:i') }}</span>
                            </div>
                        </div>

                        <div class="p-6 mt-auto bg-slate-50">
                            @if(in_array($exam->id, $userSubmissions))
                                @if($exam->is_published)
                                    <a href="{{ route('student.results.show', $exam->id) }}" 
                                       class="block w-full text-center bg-blue-400 border-4 border-black py-3 font-black text-xs uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                                        VIEW_RESULTS
                                    </a>
                                @else
                                    <div class="flex flex-col items-center">
                                        <button class="w-full bg-emerald-400 border-4 border-black py-3 font-black text-xs uppercase tracking-widest opacity-50 cursor-not-allowed" disabled>
                                            STATUS_SUBMITTED
                                        </button>
                                        <p class="mt-2 text-[9px] font-black text-slate-400 uppercase tracking-tighter italic">// Processing_Grade...</p>
                                    </div>
                                @endif

                            @elseif($now->lt($exam->start_time))
                                <button class="w-full bg-slate-200 border-4 border-black text-slate-400 py-3 font-black text-xs uppercase tracking-widest flex items-center justify-center cursor-not-allowed" disabled>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    ACCESS_LOCKED
                                </button>

                            @elseif($now->gt($exam->end_time))
                                <button class="w-full bg-red-100 border-4 border-black text-red-500 py-3 font-black text-xs uppercase tracking-widest cursor-not-allowed" disabled>
                                    SEQUENCE_EXPIRED
                                </button>

                            @else
                                <a href="{{ route('student.exams.show', $exam->id) }}" 
                                   class="block w-full text-center bg-yellow-400 border-4 border-black text-black py-4 font-black text-sm uppercase tracking-widest shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all active:bg-black active:text-white">
                                    INITIATE_EXAM_START
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border-4 border-dashed border-black py-20 text-center">
                        <p class="text-2xl mb-4">🚫</p>
                        <p class="text-black font-black uppercase tracking-widest">No_Active_Exams_Found</p>
                        <p class="text-slate-400 text-[10px] mt-2 font-mono italic">// Request higher-level clearance from lecturer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>