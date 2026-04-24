<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto border-b-4 border-black pb-4">
            <p class="text-[10px] font-bold text-black uppercase tracking-[0.3em] mb-1 font-mono">// ADMIN_STORAGE_SYSTEM</p>
            <h2 class="text-3xl font-black text-black tracking-tighter uppercase font-mono">
                Pusat Bank Soalan
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-[#e5e5e5] min-h-screen font-mono text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center gap-4">
                <div class="bg-yellow-400 border-4 border-black p-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] flex items-center gap-4">
                    <div class="p-2 bg-black text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black uppercase leading-none text-black/60">Total_Records</p>
                        <p class="text-2xl font-black">{{ count($exams) }} EXAMS</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border-4 border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black text-white border-b-4 border-black text-xs uppercase tracking-widest font-black">
                                <th class="p-5">Exam_Title</th>
                                <th class="p-5 text-center">Data_Stat</th>
                                <th class="p-5 text-center">Start_Sequence</th>
                                <th class="p-5 text-right">Export_Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y-4 divide-black">
                            @foreach($exams as $exam)
                            <tr class="group hover:bg-yellow-50 transition-none">
                                <td class="p-5 border-r-4 border-black">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-black text-white flex items-center justify-center font-black text-xl border-2 border-black group-hover:bg-yellow-400 group-hover:text-black transition-none">
                                            {{ substr($exam->title, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black uppercase text-base leading-none">{{ $exam->title }}</p>
                                            <p class="text-[10px] font-bold text-slate-500 mt-1">UUID: #{{ str_pad($exam->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-5 border-r-4 border-black text-center">
                                    <span class="inline-block px-3 py-1 bg-white border-2 border-black font-black text-[11px] uppercase shadow-[3px_3px_0px_0px_rgba(0,0,0,1)]">
                                        {{ $exam->questions_count }} Q_UNITS
                                    </span>
                                </td>
                                <td class="p-5 border-r-4 border-black text-center">
                                    <p class="text-xs font-black uppercase">{{ \Carbon\Carbon::parse($exam->start_time)->format('d/M/Y') }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 italic leading-none">{{ \Carbon\Carbon::parse($exam->start_time)->format('H:i A') }}</p>
                                </td>
                                <td class="p-5 bg-slate-50/50">
                                    <div class="flex justify-end gap-2">
                                        {{-- Excel --}}
                                        <a href="{{ route('admin.exams.export-excel', $exam->id) }}" 
                                           class="px-4 py-2 bg-emerald-400 border-2 border-black font-black text-[10px] uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all"
                                           title="Export Excel">
                                            XLS_FILE
                                        </a>

                                        {{-- PDF --}}
                                        <a href="{{ route('admin.exams.export-pdf', $exam->id) }}" 
                                           class="px-4 py-2 bg-red-400 border-2 border-black font-black text-[10px] uppercase shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all"
                                           title="Export PDF">
                                            PDF_FILE
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-10">
                <a href="{{ route('admin.dashboard') }}" class="inline-block px-6 py-3 border-4 border-black bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-none">
                    [ BACK_TO_CONTROL_ROOM ]
                </a>
            </div>

        </div>
    </div>
</x-app-layout>