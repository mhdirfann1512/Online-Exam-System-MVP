<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between max-w-7xl mx-auto">
            <div>
                <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">Resource Library</p>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-tight">
                    Bank Soalan
                </h2>
            </div>
            <a href="{{ route('admin.questions.index', $targetExamId) }}" class="text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Exam
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 p-6 bg-indigo-900 rounded-[2rem] text-white shadow-xl shadow-indigo-100 flex items-center justify-between relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold">Target Exam:</h3>
                    <p class="text-indigo-200 font-medium">{{ $exam->title ?? 'N/A' }}</p>
                </div>
                <svg class="absolute -right-10 opacity-10 w-40 h-40" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path></svg>
            </div>

            <div class="space-y-4">
                @foreach($allExams as $ex)
                    <div class="bg-white rounded-[1.5rem] border border-slate-100 shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md">
                        <div class="p-5 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-black text-slate-800 leading-tight">{{ $ex->title }}</h4>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $ex->questions_count }} Soalan Tersedia</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <form action="{{ route('admin.questions.copy_exam', $targetExamId) }}" method="POST" onsubmit="return confirm('Salin semua soalan dari exam ini?')">
                                    @csrf
                                    <input type="hidden" name="source_exam_id" value="{{ $ex->id }}">
                                    <button type="submit" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all">
                                        Salin Semua
                                    </button>
                                </form>

                                <button onclick="toggleExam({{ $ex->id }})" class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all flex items-center shadow-lg shadow-slate-200">
                                    Pilih Soalan
                                    <svg id="icon-{{ $ex->id }}" class="w-3 h-3 ml-2 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div id="exam-list-{{ $ex->id }}" class="hidden border-t border-slate-50 bg-slate-50/30 p-6">
                            <form action="{{ route('admin.questions.copy_selected', $targetExamId) }}" method="POST">
                                @csrf
                                <div class="space-y-2 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($ex->questions as $q)
                                        <label class="group flex items-start p-4 bg-white rounded-2xl border border-slate-100 hover:border-indigo-200 cursor-pointer transition-all has-[:checked]:bg-indigo-50/50 has-[:checked]:border-indigo-200">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                            </div>
                                            <div class="ml-4 text-sm">
                                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-tighter {{ $q->type == 'mcq' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }} mb-1">
                                                    {{ $q->type }}
                                                </span>
                                                <p class="font-bold text-slate-700 leading-snug">{{ $q->question_text }}</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                
                                <div class="mt-6 flex justify-between items-center bg-white p-4 rounded-2xl border border-slate-100">
                                    <p class="text-xs text-slate-400 font-medium">Sila tandakan soalan yang ingin disalin.</p>
                                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all">
                                        Import Terpilih
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>

    <script>
        function toggleExam(id) {
            const element = document.getElementById('exam-list-' + id);
            const icon = document.getElementById('icon-' + id);
            
            element.classList.toggle('hidden');
            
            if (element.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>
</x-app-layout>