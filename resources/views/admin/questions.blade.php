<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto">
            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em] mb-1">Question Management</p>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight leading-tight">
                {{ $exam->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 p-8 md:p-10">
                        <h3 class="text-xl font-black text-slate-800 mb-8 flex items-center">
                            <span class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center mr-3 text-sm">1</span>
                            Add New Question
                        </h3>
                        
                        <form action="{{ route('admin.questions.store', $exam->id) }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 bg-slate-50 rounded-3xl border border-slate-100">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Question Type</label>
                                    <select name="type" id="type-select" onchange="toggleFields()" class="w-full rounded-xl border-slate-200 focus:ring-slate-900 focus:border-slate-900 font-bold text-slate-700 shadow-sm">
                                        <option value="mcq">Multiple Choice (MCQ)</option>
                                        <option value="subjective">Subjective / Keywords</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Entry Mode</label>
                                    <div class="flex gap-2 p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
                                        <label class="flex-1 text-center py-2 px-4 rounded-lg cursor-pointer transition-all has-[:checked]:bg-slate-900 has-[:checked]:text-white text-slate-400 font-bold text-sm">
                                            <input type="radio" name="entry_mode" value="single" checked onchange="toggleFields()" class="hidden">
                                            Single
                                        </label>
                                        <label class="flex-1 text-center py-2 px-4 rounded-lg cursor-pointer transition-all has-[:checked]:bg-slate-900 has-[:checked]:text-white text-slate-400 font-bold text-sm">
                                            <input type="radio" name="entry_mode" value="bulk" onchange="toggleFields()" class="hidden">
                                            Bulk Text
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="single-entry-fields" class="space-y-6">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Question Text</label>
                                    <textarea name="question_text" rows="3" placeholder="Enter your question clearly..." class="w-full rounded-2xl border-slate-200 focus:ring-slate-900 focus:border-slate-900 p-4 font-medium text-slate-700 transition-all"></textarea>
                                </div>

                                <div id="mcq-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="relative group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-300 group-focus-within:text-indigo-500 transition-colors">A</span>
                                        <input type="text" name="option_a" placeholder="First option" class="w-full pl-10 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                    </div>
                                    <div class="relative group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-300 group-focus-within:text-indigo-500 transition-colors">B</span>
                                        <input type="text" name="option_b" placeholder="Second option" class="w-full pl-10 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                    </div>
                                    <div class="relative group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-300 group-focus-within:text-indigo-500 transition-colors">C</span>
                                        <input type="text" name="option_c" placeholder="Third option" class="w-full pl-10 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                    </div>
                                    <div class="relative group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-slate-300 group-focus-within:text-indigo-500 transition-colors">D</span>
                                        <input type="text" name="option_d" placeholder="Fourth option" class="w-full pl-10 rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 font-medium">
                                    </div>
                                    <div class="md:col-span-2 mt-2">
                                        <label class="block text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-2">Set Correct Answer</label>
                                        <select name="correct_answer" id="correct-answer-mcq" class="w-full rounded-xl border-slate-200 bg-indigo-50 font-black text-indigo-700">
                                            <option value="A">OPTION A</option>
                                            <option value="B">OPTION B</option>
                                            <option value="C">OPTION C</option>
                                            <option value="D">OPTION D</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="subjective-fields" class="hidden">
                                    <label class="block text-[10px] font-black text-purple-500 uppercase tracking-widest mb-2">Target Keywords (Comma separated)</label>
                                    <input type="text" name="correct_answer_subjective" id="correct-answer-sub" placeholder="e.g: merdeka, 1957, tunku abdul rahman" class="w-full rounded-xl border-slate-200 bg-purple-50 font-medium placeholder:text-purple-300">
                                </div>
                            </div>

                            <div id="bulk-entry-fields" class="hidden space-y-4">
                                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                                    <div id="bulk-guide-mcq" class="text-xs text-amber-800 space-y-1">
                                        <p class="font-black">💡 FORMAT MCQ:</p>
                                        <p class="font-mono bg-white/50 p-2 rounded">1. Question?<br>A. Opt 1 | B. Opt 2 | C. Opt 3 | D. Opt 4<br>ANSWER: B</p>
                                    </div>
                                    <div id="bulk-guide-sub" class="hidden text-xs text-amber-800 space-y-1">
                                        <p class="font-black">💡 FORMAT SUBJECTIVE:</p>
                                        <p class="font-mono bg-white/50 p-2 rounded">1. Question?<br>ANSWER: key1, key2</p>
                                    </div>
                                </div>
                                <textarea name="bulk_text" rows="8" class="w-full rounded-2xl border-slate-200 font-mono text-sm p-4 focus:ring-slate-900" placeholder="Paste multiple questions here..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black shadow-xl shadow-slate-200 hover:bg-slate-800 active:scale-[0.98] transition-all">
                                Save Questions
                            </button>
                        </form>
                    </div>

                    <div class="space-y-4">
                        <h3 class="px-2 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Questions in this Exam ({{ count($questions) }})</h3>
                        @foreach($questions as $index => $q)
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                                <div class="absolute top-0 right-0 p-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">ID: #{{ $q->id }}</span>
                                </div>
                                <div class="flex gap-4">
                                    <span class="flex-none w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-500">
                                        {{ $index + 1 }}
                                    </span>
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-tighter {{ $q->type == 'mcq' ? 'bg-blue-100 text-blue-600' : 'bg-purple-100 text-purple-600' }}">
                                                {{ $q->type }}
                                            </span>
                                        </div>
                                        <p class="font-bold text-slate-800 mb-4">{{ $q->question_text }}</p>
                                        
                                        @if($q->type == 'mcq')
                                            <div class="grid grid-cols-2 gap-2 mb-4">
                                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                                    <div class="text-xs p-2 rounded-lg {{ $q->correct_answer == $opt ? 'bg-emerald-50 text-emerald-700 font-bold border border-emerald-100' : 'bg-slate-50 text-slate-500' }}">
                                                        <span class="opacity-50 mr-1">{{ $opt }}.</span> {{ $q->options[$opt] ?? '-' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3 bg-emerald-50 border border-emerald-100 rounded-xl mb-4">
                                                <p class="text-[10px] font-black text-emerald-600 uppercase mb-1 tracking-widest">Correct Keywords</p>
                                                <p class="text-xs font-bold text-emerald-800">{{ $q->correct_answer }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

<div class="space-y-6">
    <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
        <div class="absolute -right-2 -top-2 w-16 h-16 bg-indigo-50 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
        
        <h3 class="relative z-10 text-sm font-black text-slate-800 uppercase tracking-widest mb-4 flex items-center">
            <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            Existing Resources
        </h3>
        <p class="relative z-10 text-xs text-slate-500 mb-6 leading-relaxed">
            Gunakan soalan sedia ada daripada koleksi soalan yang pernah anda buat sebelum ini.
        </p>
        <a href="{{ route('admin.questions.bank', $exam->id) }}" 
           class="relative z-10 w-full inline-flex justify-center items-center px-6 py-4 bg-indigo-50 text-indigo-700 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
            Browse Question Bank
        </a>
    </div>

    <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl shadow-slate-300 relative overflow-hidden">
        <div class="absolute right-0 bottom-0 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
        
        <h3 class="text-sm font-black uppercase tracking-widest mb-4 flex items-center gap-2 text-indigo-300">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
            Quick Import
        </h3>
        
        <form action="{{ route('admin.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="relative group">
                <input type="file" name="file" id="file-upload" class="hidden" required onchange="updateFileName(this)">
                <label for="file-upload" class="flex flex-col items-center justify-center w-full p-4 border-2 border-dashed border-slate-700 rounded-2xl hover:border-indigo-500 hover:bg-slate-800 transition-all cursor-pointer">
                    <span id="file-name" class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Choose CSV/Excel File</span>
                </label>
            </div>
            <button type="submit" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-900/20 hover:bg-indigo-500 hover:-translate-y-0.5 transition-all active:scale-95">
                Upload Now
            </button>
        </form>
        
        <div class="mt-6 pt-6 border-t border-slate-800">
            <p class="text-[10px] font-black uppercase text-slate-500 mb-2 tracking-widest">Expected Headers:</p>
            <div class="bg-black/30 p-3 rounded-xl">
                <code class="text-[9px] text-indigo-200 break-all font-mono leading-loose">type, question_text, option_a, option_b, option_c, option_d, correct_answer</code>
            </div>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('type-select').value;
            const mode = document.querySelector('input[name="entry_mode"]:checked').value;
            
            document.getElementById('single-entry-fields').classList.toggle('hidden', mode === 'bulk');
            document.getElementById('bulk-entry-fields').classList.toggle('hidden', mode === 'single');

            if(mode === 'single') {
                document.getElementById('mcq-fields').classList.toggle('hidden', type !== 'mcq');
                document.getElementById('subjective-fields').classList.toggle('hidden', type !== 'subjective');
                
                const subInput = document.getElementById('correct-answer-sub');
                const mcqSelect = document.getElementById('correct-answer-mcq');
                
                if(type === 'subjective') {
                    subInput.setAttribute('name', 'correct_answer');
                    mcqSelect.removeAttribute('name');
                } else {
                    mcqSelect.setAttribute('name', 'correct_answer');
                    subInput.setAttribute('name', 'correct_answer_subjective');
                }
            }

            document.getElementById('bulk-guide-mcq').classList.toggle('hidden', type !== 'mcq');
            document.getElementById('bulk-guide-sub').classList.toggle('hidden', type !== 'subjective');
        }
        // Run once on load
        toggleFields();
    </script>

    <script>
    function updateFileName(input) {
        const label = document.getElementById('file-name');
        if (input.files && input.files.length > 0) {
            label.textContent = input.files[0].name;
            label.classList.replace('text-slate-400', 'text-indigo-400');
        }
    }
</script>
</x-app-layout>