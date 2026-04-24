<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-medium tracking-tight text-slate-800">
            {{ __('Senarai Peperiksaan') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <div class="py-10 bg-slate-50/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                @php
                    $studentStats = [
                        ['label' => 'Upcoming Exams', 'value' => $upcomingExams, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600'],
                        ['label' => 'Completed', 'value' => $completedExams, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
                        ['label' => 'Average Score', 'value' => number_format($averageScore, 1) . '%', 'icon' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600'],
                    ];
                @endphp

                @foreach($studentStats as $stat)
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center transition-transform hover:scale-[1.02]">
                    <div class="p-4 rounded-xl {{ $stat['bg'] }} {{ $stat['text'] }}">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $stat['label'] }}</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stat['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="flex items-center mb-6">
                <div class="w-1 h-6 bg-slate-900 rounded-full mr-3"></div>
                <h3 class="text-lg font-bold text-slate-800 tracking-tight">Available Peperiksaan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($exams as $exam)
                    <div class="flex flex-col bg-white border border-slate-100 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        
                        <div class="p-6 pb-0">
                            <div class="flex justify-between items-start mb-4">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-tighter">
                                    {{ $exam->duration_minutes }} Mins
                                </span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-4">
                                {{ $exam->title }}
                            </h4>
                        </div>

                        <div class="px-6 py-4 bg-slate-50/50 space-y-3">
                            <div class="flex items-center text-xs text-slate-500">
                                <svg class="w-4 h-4 mr-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium">Starts: {{ \Carbon\Carbon::parse($exam->start_time)->format('d M, h:i A') }}</span>
                            </div>
                            <div class="flex items-center text-xs text-slate-500">
                                <svg class="w-4 h-4 mr-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-medium text-red-400">Ends: {{ \Carbon\Carbon::parse($exam->end_time)->format('d M, h:i A') }}</span>
                            </div>
                        </div>

                        <div class="p-6 mt-auto">
                            @if(in_array($exam->id, $userSubmissions))
                                @if($exam->is_published)
                                    <a href="{{ route('student.results.show', $exam->id) }}" 
                                       class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-md shadow-blue-100">
                                        Lihat Keputusan
                                    </a>
                                @else
                                    <div class="flex flex-col items-center">
                                        <button class="w-full bg-emerald-50 text-emerald-600 border border-emerald-100 py-3 rounded-xl font-bold text-sm cursor-default" disabled>
                                            Selesai Dihantar
                                        </button>
                                        <p class="mt-2 text-[10px] text-slate-400 italic">Keputusan belum diterbitkan</p>
                                    </div>
                                @endif

                            @elseif($now->lt($exam->start_time))
                                <button class="w-full bg-slate-100 text-slate-400 py-3 rounded-xl font-bold text-sm flex items-center justify-center cursor-not-allowed" disabled>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Locked
                                </button>

                            @elseif($now->gt($exam->end_time))
                                <button class="w-full bg-red-50 text-red-400 border border-red-100 py-3 rounded-xl font-bold text-sm cursor-not-allowed" disabled>
                                    Telah Tamat
                                </button>

                            @else
                                <a href="{{ route('student.exams.show', $exam->id) }}" 
                                   class="block w-full text-center bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-bold text-sm transition-all shadow-lg shadow-slate-200 active:scale-[0.98]">
                                    Start Exam
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white border border-dashed border-slate-200 rounded-3xl py-20 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 rounded-full mb-4">
                            <span class="text-2xl">📅</span>
                        </div>
                        <p class="text-slate-500 font-bold">No exams available at the moment.</p>
                        <p class="text-slate-400 text-xs mt-1">Please check back later or contact your lecturer.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>