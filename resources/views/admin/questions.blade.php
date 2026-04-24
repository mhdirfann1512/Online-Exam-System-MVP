<x-app-layout>
    <x-slot name="header">
        <div class="border-b-4 border-black pb-2">
            <p class="text-[10px] font-bold text-black uppercase tracking-widest mb-1 font-mono">// QUESTION_DATABASE</p>
            <h2 class="text-2xl font-black text-black tracking-tighter uppercase font-mono">
                {{ $exam->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-10 bg-[#f0f0f0] min-h-screen font-mono text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-yellow-200 border-4 border-black p-4 mb-6 flex items-center shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                    <span class="font-black uppercase text-sm">[ SUCCESS: {{ session('success') }} ]</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                
                <div class="lg:col-span-2 space-y-10">
                    <div class="bg-white border-4 border-black p-6 md:p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <h3 class="text-lg font-black uppercase mb-8 border-b-2 border-black inline-block">
                            + ADD_NEW_ENTRY
                        </h3>
                        
                        <form action="{{ route('admin.questions.store', $exam->id) }}" method="POST" class="space-y-8">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-4 border-black bg-black">
                                <div class="bg-white p-4 border-r-4 border-black md:border-r-4">
                                    <label class="block text-xs font-black uppercase mb-2 italic">Type:</label>
                                    <select name="type" id="type-select" onchange="toggleFields()" class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 font-bold uppercase text-xs">
                                        <option value="mcq">Multiple Choice (MCQ)</option>
                                        <option value="subjective">Subjective / Keywords</option>
                                    </select>
                                </div>
                                <div class="bg-white p-4">
                                    <label class="block text-xs font-black uppercase mb-2 italic">Mode:</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs">
                                            <input type="radio" name="entry_mode" value="single" checked onchange="toggleFields()" class="w-4 h-4 border-2 border-black text-black focus:ring-0">
                                            SINGLE
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer font-bold text-xs">
                                            <input type="radio" name="entry_mode" value="bulk" onchange="toggleFields()" class="w-4 h-4 border-2 border-black text-black focus:ring-0">
                                            BULK_TEXT
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div id="single-entry-fields" class="space-y-6">
                                <div>
                                    <label class="block text-xs font-black uppercase mb-2 italic underline">Question_Prompt:</label>
                                    <textarea name="question_text" rows="3" placeholder="INPUT QUESTION TEXT..." class="w-full border-2 border-black focus:bg-yellow-50 focus:ring-0 p-4 font-bold text-sm"></textarea>
                                </div>

                                <div id="mcq-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach(['A', 'B', 'C', 'D'] as $label)
                                    <div class="flex items-center border-2 border-black bg-white group">
                                        <span class="px-4 py-2 bg-black text-white font-black">{{ $label }}</span>
                                        <input type="text" name="option_{{ strtolower($label) }}" placeholder="Option {{ $label }}" class="flex-grow border-0 focus:ring-0 text-sm font-bold">
                                    </div>
                                    @endforeach
                                    
                                    <div class="md:col-span-2 mt-4 bg-black p-4 border-2 border-black">
                                        <label class="block text-xs font-black text-white uppercase mb-2 tracking-widest italic">Set_Correct_Answer:</label>
                                        <select name="correct_answer" id="correct-answer-mcq" class="w-full border-2 border-white bg-black text-white font-black uppercase text-xs focus:ring-0">
                                            <option value="A">OPTION A</option>
                                            <option value="B">OPTION B</option>
                                            <option value="C">OPTION C</option>
                                            <option value="D">OPTION D</option>
                                        </select>
                                    </div>
                                </div>

                                <div id="subjective-fields" class="hidden">
                                    <label class="block text-xs font-black uppercase mb-2 italic underline text-red-600">Target_Keywords (Split by comma):</label>
                                    <input type="text" name="correct_answer_subjective" id="correct-answer-sub" placeholder="KEYWORD_1, KEYWORD_2..." class="w-full border-2 border-black bg-white font-bold p-3">
                                </div>
                            </div>

                            <div id="bulk-entry-fields" class="hidden space-y-4">
                                <div class="p-4 bg-yellow-100 border-2 border-black text-[10px] space-y-1">
                                    <p class="font-black uppercase">⚠️ BULK_IMPORT_FORMAT:</p>
                                    <p class="font-mono">MCQ: Q? | A. Opt | B. Opt | ANSWER: A</p>
                                    <p class="font-mono">SUB: Q? | ANSWER: Key1, Key2</p>
                                </div>
                                <textarea name="bulk_text" rows="8" class="w-full border-2 border-black font-mono text-xs p-4 focus:bg-yellow-50" placeholder="PASTE_DATA_HERE..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-4 bg-black text-white border-4 border-black font-black uppercase tracking-widest hover:bg-white hover:text-black transition-none active:translate-x-1 active:translate-y-1">
                                SAVE_TO_DATABASE
                            </button>
                        </form>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-xs font-black uppercase tracking-widest underline italic">Current_Question_Stack ({{ count($questions) }})</h3>
                        
                        @foreach($questions as $index => $q)
                            <div class="bg-white border-2 border-black p-6 relative overflow-hidden shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                                <div class="flex items-start gap-4">
                                    <span class="bg-black text-white px-2 py-1 text-xs font-black">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="px-2 py-0.5 border border-black text-[10px] font-black uppercase italic bg-slate-100">
                                                MODE: {{ $q->type }}
                                            </span>
                                        </div>
                                        <p class="font-black text-sm uppercase leading-tight mb-4">{{ $q->question_text }}</p>
                                        
                                        @if($q->type == 'mcq')
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach(['A', 'B', 'C', 'D'] as $opt)
                                                    <div class="text-[10px] p-2 border {{ $q->correct_answer == $opt ? 'bg-black text-white font-black' : 'border-slate-200 text-slate-400' }}">
                                                        {{ $opt }}. {{ $q->options[$opt] ?? '-' }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="p-3 bg-slate-100 border-l-4 border-black">
                                                <p class="text-[9px] font-black uppercase mb-1">Answer_Keys:</p>
                                                <p class="text-xs font-bold italic">{{ $q->correct_answer }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="bg-white border-4 border-black p-6 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <h3 class="text-xs font-black uppercase border-b-2 border-black mb-4 pb-1 inline-block italic">Question_Bank</h3>
                        <p class="text-[10px] mb-6 leading-tight font-bold italic text-slate-500 underline decoration-dotted">
                            PULLED_RESOURCES_FROM_ARCHIVE.
                        </p>
                        <a href="{{ route('admin.questions.bank', $exam->id) }}" 
                           class="w-full block text-center py-3 border-2 border-black font-black text-xs uppercase hover:bg-black hover:text-white transition-none active:translate-y-1">
                            BROWSE_ARCHIVE
                        </a>
                    </div>

                    <div class="bg-black text-white p-6 border-4 border-black shadow-[8px_8px_0px_0px_rgba(255,255,255,1),8px_8px_0px_2px_rgba(0,0,0,1)]">
                        <h3 class="text-xs font-black uppercase mb-6 flex items-center gap-2 italic">
                            >> QUICK_IMPORT
                        </h3>
                        
                        <form action="{{ route('admin.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <input type="file" name="file" id="file-upload" class="hidden" required onchange="updateFileName(this)">
                            <label for="file-upload" class="block w-full p-4 border-2 border-dashed border-white text-center cursor-pointer hover:bg-white hover:text-black transition-none">
                                <span id="file-name" class="text-[10px] font-black uppercase">SELECT_CSV_FILE</span>
                            </label>
                            
                            <button type="submit" class="w-full py-3 bg-white text-black font-black text-xs uppercase hover:invert transition-none">
                                EXECUTE_UPLOAD
                            </button>
                        </form>
                        
                        <div class="mt-6 pt-6 border-t border-white/20">
                            <p class="text-[9px] font-black uppercase text-slate-400 mb-2 underline decoration-double">Expected_Headers:</p>
                            <code class="text-[9px] text-white/60 break-all leading-loose italic">type, question_text, option_a, option_b, option_c, option_d, correct_answer</code>
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
        toggleFields();

        function updateFileName(input) {
            const label = document.getElementById('file-name');
            if (input.files && input.files.length > 0) {
                label.textContent = "> " + input.files[0].name.toUpperCase();
                label.classList.add('text-yellow-400');
            }
        }
    </script>
</x-app-layout>