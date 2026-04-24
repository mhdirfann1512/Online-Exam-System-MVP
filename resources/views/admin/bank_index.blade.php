<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">Administrator Tools</p>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-tight">
                Pusat Bank Soalan
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="max-w-xl">
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Arkib Peperiksaan Master</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Pusat kawalan untuk memuat turun semua koleksi soalan. Anda boleh mengeksport data ke dalam format **Excel** untuk analisis data atau **PDF** untuk salinan fizikal/cetakan.
                    </p>
                </div>
                <div class="flex -space-x-2">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 border-4 border-white flex items-center justify-center text-emerald-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h4m-4 4l4-4m-4 4l-4-4"></path></svg>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-red-100 border-4 border-white flex items-center justify-center text-red-600 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-xl shadow-slate-200/60 rounded-[2rem] border border-slate-100">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tajuk Peperiksaan</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Statistik</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Tarikh Mula</th>
                                <th class="p-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Eksport Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($exams as $exam)
                                <tr class="group hover:bg-slate-50/50 transition-all">
                                    <td class="p-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs group-hover:scale-110 transition-transform">
                                                {{ substr($exam->title, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 tracking-tight">{{ $exam->title }}</p>
                                                <p class="text-[10px] text-slate-400 font-medium">ID: #{{ str_pad($exam->id, 5, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-black">
                                            <svg class="w-3 h-3 mr-1.5 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>
                                            {{ $exam->questions_count }} Soalan
                                        </span>
                                    </td>
                                    <td class="p-6 text-center text-sm font-bold text-slate-500">
                                        {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="p-6">
                                        <div class="flex justify-end gap-3">
                                            {{-- Excel Action --}}
                                            <a href="{{ route('admin.exams.export-excel', $exam->id) }}" 
                                               class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm group/btn"
                                               title="Download Excel">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </a>

                                            {{-- PDF Action --}}
                                            <a href="{{ route('admin.exams.export-pdf', $exam->id) }}" 
                                               class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm"
                                               title="Download PDF">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8 px-6 flex justify-between items-center text-[10px] font-black text-slate-300 uppercase tracking-widest">
                <span>Total Exams: {{ count($exams) }}</span>
                <span>LMS Archive System v2.0</span>
            </div>
        </div>
    </div>
</x-app-layout>