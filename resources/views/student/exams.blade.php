<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            {{ __('Peperiksaan') }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-white border-2 border-black text-black px-4 py-3 text-xs font-bold uppercase tracking-widest">
                [ STATUS: BERJAYA ] {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
            <div class="bg-black text-white px-4 py-3 text-xs font-bold uppercase tracking-widest">
                [ RALAT: SISTEM ] {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-black mb-10">
                <div class="bg-white p-6 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Peperiksaan akan datang</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $upcomingExams }}</p>
                </div>

                <div class="bg-white p-6 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Peperiksaan Dijawab</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $completedExams }}</p>
                </div>

                <div class="bg-white p-6">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Purata Markah</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ number_format($averageScore, 1) }}%</p>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-xs font-bold uppercase tracking-widest border-b-2 border-black pb-1 inline-block">Senarai Peperiksaan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                @forelse($exams as $exam)
                    <div class="bg-white border border-black p-6 flex flex-col justify-between hover:bg-gray-50 transition-colors">
                        <div>
                            <div class="mb-4">
                                <h4 class="text-lg font-bold text-black uppercase leading-tight mt-1">{{ $exam->title }}</h4>
                            </div>
                            
                            <div class="space-y-1 mb-8">
                                <div class="flex items-center text-[11px] font-bold uppercase text-gray-600">
                                    <span class="w-20">Duration</span>
                                    <span class="text-black italic">: {{ $exam->duration_minutes }} Minutes</span>
                                </div>
                                <div class="flex items-center text-[11px] font-bold uppercase text-gray-600">
                                    <span class="w-20">Start</span>
                                    <span class="text-black italic">: {{ \Carbon\Carbon::parse($exam->start_time)->format('d.m.y | h:i A') }}</span>
                                </div>
                                <div class="flex items-center text-[11px] font-bold uppercase text-gray-600">
                                    <span class="w-20">End</span>
                                    <span class="text-black italic">: {{ \Carbon\Carbon::parse($exam->end_time)->format('d.m.y | h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-black border-dotted flex flex-col gap-2">
                            @if(in_array($exam->id, $userSubmissions))
                                @if($exam->is_published)
                                    <a href="{{ route('student.results.show', $exam->id) }}" 
                                    class="text-xs font-bold uppercase underline hover:no-underline text-black">
                                        [ LIHAT KEPUTUSAN RASMI ]
                                    </a>
                                @else
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold uppercase text-gray-400">[ STATUS: SELESAI DIHANTAR ]</span>
                                        <span class="text-[9px] italic text-gray-400 uppercase mt-1">*Keputusan dalam proses penerbitan</span>
                                    </div>
                                @endif

                            @elseif($now->lt($exam->start_time))
                                <span class="text-[10px] font-bold uppercase text-gray-400">
                                    [ BELUM MULA: {{ \Carbon\Carbon::parse($exam->start_time)->format('d-m-Y\ H:i') }} ]
                                </span>

                            @elseif($now->gt($exam->end_time))
                                <span class="text-[10px] font-bold uppercase text-gray-400 italic underline decoration-red-500">
                                    [ REKOD DITUTUP / TAMAT ]
                                </span>

                            @else
                                <a href="{{ route('student.exams.show', $exam->id) }}" 
                                class="text-xs font-bold uppercase underline hover:no-underline text-black tracking-widest">
                                    [ JAWAB SEKARANG >> ]
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 border-2 border-dashed border-black text-center bg-gray-50">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-gray-400 italic">Tiada Rekod Peperiksaan Ditemui Dalam Arkib</p>
                    </div>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>