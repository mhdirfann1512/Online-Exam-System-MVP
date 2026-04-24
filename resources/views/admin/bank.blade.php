<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto border-b-4 border-black pb-4">
            <p class="text-[10px] font-bold text-black uppercase tracking-[0.3em] mb-1 font-mono">// RESOURCE_QUERY_BANK</p>
            <h2 class="text-3xl font-black text-black tracking-tighter uppercase font-mono">
                Bank Soalan
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-[#e5e5e5] min-h-screen font-mono text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="space-y-6">
                @foreach($allExams as $ex)
                    <div class="bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                        <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 bg-black text-white flex items-center justify-center font-black text-2xl border-2 border-black shadow-[4px_4px_0px_0px_rgba(255,255,0,1)]">
                                    {{ substr($ex->title, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-black text-xl uppercase tracking-tighter leading-none">{{ $ex->title }}</h4>
                                    <p class="text-[10px] font-black bg-black text-yellow-400 inline-block px-2 py-0.5 mt-2 uppercase">
                                        AVAIL_UNITS: {{ $ex->questions_count }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <form action="{{ route('admin.questions.copy_exam', $targetExamId) }}" method="POST" onsubmit="return confirm('Salin semua soalan?')">
                                    @csrf
                                    <input type="hidden" name="source_exam_id" value="{{ $ex->id }}">
                                    <button type="submit" class="px-6 py-3 bg-emerald-400 border-4 border-black font-black text-xs uppercase tracking-widest hover:bg-black hover:text-emerald-400 transition-none">
                                        COPY_ALL
                                    </button>
                                </form>

                                <button onclick="toggleExam({{ $ex->id }})" class="px-6 py-3 bg-black text-white border-4 border-black font-black text-xs uppercase tracking-widest hover:bg-white hover:text-black transition-none flex items-center">
                                    SELECT_MANUAL
                                    <svg id="icon-{{ $ex->id }}" class="w-4 h-4 ml-2 transition-transform duration-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div id="exam-list-{{ $ex->id }}" class="hidden border-t-4 border-black bg-yellow-50 p-8">
                            <form action="{{ route('admin.questions.copy_selected', $targetExamId) }}" method="POST">
                                @csrf
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-4 custom-scrollbar">
                                    @foreach($ex->questions as $q)
                                        <label class="group flex items-start p-4 bg-white border-2 border-black hover:bg-black hover:text-white cursor-pointer transition-none has-[:checked]:bg-yellow-200 has-[:checked]:text-black">
                                            <div class="flex items-center h-5">
                                                <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" class="w-6 h-6 border-4 border-black text-black focus:ring-0">
                                            </div>
                                            <div class="ml-4">
                                                <span class="inline-block px-2 py-0.5 border border-black text-[9px] font-black uppercase mb-1 {{ $q->type == 'mcq' ? 'bg-blue-400 text-black' : 'bg-purple-400 text-black' }}">
                                                    TYPE_{{ $q->type }}
                                                </span>
                                                <p class="font-bold text-sm leading-tight italic">"{{ $q->question_text }}"</p>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                
                                <div class="mt-8 p-4 border-4 border-black border-dashed flex justify-between items-center">
                                    <p class="text-[10px] font-black uppercase">>> Check boxes to initialize import sequence</p>
                                    <button type="submit" class="px-8 py-4 bg-black text-white border-4 border-black font-black text-xs uppercase tracking-[0.2em] hover:bg-yellow-400 hover:text-black transition-none">
                                        EXECUTE_IMPORT
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 pt-8 border-t-4 border-black">
                <a href="{{ route('admin.questions.index', $targetExamId) }}" class="inline-flex items-center font-black text-xs uppercase tracking-widest group">
                    <span class="bg-black text-white p-2 group-hover:bg-yellow-400 group-hover:text-black transition-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </span>
                    <span class="ml-4 underline decoration-4">Return_to_Current_Exam_Manifest</span>
                </a>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #000; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ffff00; border: 2px solid #000; }
        input[type="checkbox"] { border-radius: 0; }
    </style>

    <script>
        function toggleExam(id) {
            const element = document.getElementById('exam-list-' + id);
            const icon = document.getElementById('icon-' + id);
            element.classList.toggle('hidden');
            icon.style.transform = element.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    </script>
</x-app-layout>