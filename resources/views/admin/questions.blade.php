<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Urus Soalan: {{ $exam->title }}
        </h2>
    </x-slot>

    @if(session('success'))
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">
            <div class="bg-white border-2 border-black text-black px-4 py-2 text-xs font-bold uppercase tracking-widest">
                [ STATUS: {{ session('success') }} ]
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 mt-4">
            <div class="bg-black text-white px-4 py-2 text-xs font-bold uppercase">
                Ralat Kemasukan Data:
                <ul class="mt-1 list-disc list-inside font-normal normal-case">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 bg-white border border-black">

                <h3 class="mb-6 text-sm font-bold uppercase border-b border-black pb-2">Daftar Soalan Baru</h3>
                
                <form action="{{ route('admin.questions.store', $exam->id) }}" method="POST" class="mb-10">
                    @csrf
                    
                    <div class="flex flex-wrap gap-6 mb-6 items-end pb-6 border-b border-dotted border-gray-400">
                        <div class="w-full md:w-1/4">
                            <label class="block text-xs font-bold uppercase mb-1">Jenis Soalan:</label>
                            <select name="type" id="type-select" class="w-full border-black focus:ring-0 text-sm py-1" onchange="toggleFields()">
                                <option value="mcq">ANEKA PILIHAN (MCQ)</option>
                                <option value="subjective">SUBJEKTIF</option>
                            </select>
                        </div>

                        <div class="w-full md:w-1/2 p-3 bg-gray-50 border border-black flex items-center gap-6">
                            <span class="text-xs font-bold uppercase italic">Mod Kemasukan:</span>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="entry_mode" value="single" checked onchange="toggleFields()" class="form-radio text-black border-black focus:ring-0">
                                <span class="ml-2 text-xs font-bold uppercase tracking-tighter">Manual</span>
                            </label>
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="radio" name="entry_mode" value="bulk" onchange="toggleFields()" class="form-radio text-black border-black focus:ring-0">
                                <span class="ml-2 text-xs font-bold uppercase tracking-tighter">Pukal (Teks)</span>
                            </label>
                        </div>
                    </div>

                    <div id="single-entry-fields" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase mb-1">Pernyataan Soalan:</label>
                            <textarea name="question_text" placeholder="Masukkan teks soalan di sini..." class="w-full border-black focus:ring-0 text-sm min-h-[100px]"></textarea>
                        </div>

                        <div id="mcq-fields" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 border border-black">
                            <div>
                                <label class="text-[10px] font-bold uppercase">Pilihan A</label>
                                <input type="text" name="option_a" class="w-full border-black text-sm py-1 focus:ring-0">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase">Pilihan B</label>
                                <input type="text" name="option_b" class="w-full border-black text-sm py-1 focus:ring-0">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase">Pilihan C</label>
                                <input type="text" name="option_c" class="w-full border-black text-sm py-1 focus:ring-0">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase">Pilihan D</label>
                                <input type="text" name="option_d" class="w-full border-black text-sm py-1 focus:ring-0">
                            </div>
                            <div class="md:col-span-2 border-t border-black pt-2 mt-2">
                                <label class="text-[10px] font-bold uppercase block mb-1">Jawapan Benar:</label>
                                <select name="correct_answer" id="correct-answer-mcq" class="w-full md:w-1/3 border-black text-xs font-bold focus:ring-0">
                                    <option value="A">PILIHAN A</option>
                                    <option value="B">PILIHAN B</option>
                                    <option value="C">PILIHAN C</option>
                                    <option value="D">PILIHAN D</option>
                                </select>
                            </div>
                        </div>

                        <div id="subjective-fields" class="mt-4 hidden p-4 border border-black bg-gray-50">
                            <label class="text-xs font-bold uppercase block mb-1">Kata Kunci Jawapan (Gunakan koma sebagai pemisah):</label>
                            <input type="text" name="correct_answer_subjective" id="correct-answer-sub" placeholder="cth: merdeka,1957,tunku" class="w-full border-black focus:ring-0 text-sm">
                        </div>
                    </div>

                    <div id="bulk-entry-fields" class="hidden">
                        <div class="mb-4 p-3 bg-black text-white text-[10px] uppercase tracking-widest leading-relaxed">
                            <p id="bulk-guide-mcq"><strong>FORMAT MCQ:</strong> 1. Soalan... A. Pilihan B. Pilihan C. Pilihan D. Pilihan ANSWER: B</p>
                            <p id="bulk-guide-sub" class="hidden"><strong>FORMAT SUBJEKTIF:</strong> 1. Soalan... ANSWER: keyword1, keyword2</p>
                        </div>
                        <textarea name="bulk_text" rows="8" class="w-full border-black font-mono text-xs focus:ring-0" placeholder="Masukkan soalan beserta jawapan di sini..."></textarea>
                    </div>

                    <div class="mt-6 flex justify-between items-center border-t border-black pt-6">
                        <button type="submit" class="text-sm font-bold uppercase underline hover:no-underline tracking-tighter">
                            [ + SIMPAN REKOD SOALAN ]
                        </button>
                        
                        <a href="{{ route('admin.questions.bank', $exam->id) }}" class="text-xs font-bold uppercase underline hover:text-gray-500">
                            [ AMBIL DARI BANK SOALAN ]
                        </a>
                    </div>
                </form>

                <div class="p-6 bg-gray-50 border border-black border-dashed mb-10">
                    <h3 class="font-bold text-xs uppercase mb-4 tracking-widest text-gray-600">Muat Naik Pukal (CSV/EXCEL)</h3>
                    <form action="{{ route('admin.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            <input type="file" name="file" class="text-xs font-mono border border-black p-1 bg-white" required>
                            <button type="submit" class="text-xs font-bold uppercase underline">
                                [ MUAT NAIK ]
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-12">
                    <h3 class="font-bold mb-6 text-sm uppercase border-b-2 border-black pb-1 inline-block">Soalan</h3>
                    <p>
                        <form action="{{ route('admin.questions.destroyAll', $exam->id) }}" method="POST" class="inline" 
                            onsubmit="return confirm('ANDA PASTI? Tindakan ini akan memadam SEMUA soalan dalam peperiksaan ini dan tidak boleh dikembalikan!')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 text-xs font-bold uppercase hover:bg-red-700 transition">
                                [ !!! PADAM SEMUA SOALAN !!! ]
                            </button>
                        </form>
                    </p>
                    
                    <div class="space-y-0">
                        @foreach($questions as $q)
                        <div class="py-6 border-b border-black">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[10px] font-bold uppercase px-2 py-0.5 border border-black bg-white">{{ $q->type }}</span>
                                
                                <div class="flex gap-4">
                                    <button onclick="openEditModal({{ json_encode($q) }})" class="text-[10px] font-bold uppercase underline hover:text-blue-600">
                                        [ EDIT ]
                                    </button>

                                    <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Padam soalan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[10px] font-bold uppercase underline text-red-600 hover:no-underline">
                                            [ PADAM ]
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-sm font-bold leading-relaxed">{{ $q->question_text }}</p>
                            
                            @if($q->type == 'mcq')
                                <div class="grid grid-cols-2 gap-x-8 gap-y-1 mt-3 ml-4">
                                    <p class="text-xs text-gray-700">A. {{ $q->options['A'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-700">B. {{ $q->options['B'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-700">C. {{ $q->options['C'] ?? '-' }}</p>
                                    <p class="text-xs text-gray-700">D. {{ $q->options['D'] ?? '-' }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-4 pt-2 border-t border-dotted border-gray-300">
                                <p class="text-xs font-bold uppercase">
                                    Skema Jawapan: <span class="font-mono bg-gray-100 px-2">{{ $q->correct_answer }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white border-2 border-black w-full max-w-2xl p-6">
            <div class="flex justify-between items-center border-b border-black pb-3 mb-4">
                <h3 class="font-bold uppercase text-sm">Kemaskini Soalan</h3>
                <button onclick="closeEditModal()" class="font-bold text-lg">&times;</button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="type" id="edit_type">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase mb-1">Pernyataan Soalan:</label>
                        <textarea name="question_text" id="edit_question_text" class="w-full border-black focus:ring-0 text-sm min-h-[100px]"></textarea>
                    </div>

                    <div id="edit_mcq_fields" class="grid grid-cols-2 gap-4 bg-gray-50 p-4 border border-black">
                        <div>
                            <label class="text-[10px] font-bold uppercase">Pilihan A</label>
                            <input type="text" name="option_a" id="edit_a" class="w-full border-black text-sm py-1">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase">Pilihan B</label>
                            <input type="text" name="option_b" id="edit_b" class="w-full border-black text-sm py-1">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase">Pilihan C</label>
                            <input type="text" name="option_c" id="edit_c" class="w-full border-black text-sm py-1">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase">Pilihan D</label>
                            <input type="text" name="option_d" id="edit_d" class="w-full border-black text-sm py-1">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold uppercase block mb-1">Jawapan Benar / Kata Kunci:</label>
                        <input type="text" name="correct_answer" id="edit_correct_answer" class="w-full border-black text-sm">
                        <p class="text-[9px] mt-1 text-gray-500">*Untuk MCQ: Masukkan A, B, C atau D. Untuk Subjektif: Masukkan kata kunci.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <button type="button" onclick="closeEditModal()" class="text-xs font-bold uppercase">[ BATAL ]</button>
                    <button type="submit" class="bg-black text-white px-4 py-2 text-xs font-bold uppercase">SIMPAN PERUBAHAN</button>
                </div>
            </form>
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
            
            if(type === 'subjective') {
                document.getElementById('correct-answer-sub').setAttribute('name', 'correct_answer');
                document.getElementById('correct-answer-mcq').removeAttribute('name');
            } else {
                document.getElementById('correct-answer-mcq').setAttribute('name', 'correct_answer');
                document.getElementById('correct-answer-sub').setAttribute('name', 'correct_answer_subjective');
            }
        }

        document.getElementById('bulk-guide-mcq').classList.toggle('hidden', type !== 'mcq');
        document.getElementById('bulk-guide-sub').classList.toggle('hidden', type !== 'subjective');
    }

    function openEditModal(question) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        
        // Set URL action
        form.action = `/admin/questions/${question.id}`;
        
        // Isi data
        document.getElementById('edit_type').value = question.type;
        document.getElementById('edit_question_text').value = question.question_text;
        document.getElementById('edit_correct_answer').value = question.correct_answer;

        if(question.type === 'mcq') {
            document.getElementById('edit_mcq_fields').classList.remove('hidden');
            document.getElementById('edit_a').value = question.options?.A || '';
            document.getElementById('edit_b').value = question.options?.B || '';
            document.getElementById('edit_c').value = question.options?.C || '';
            document.getElementById('edit_d').value = question.options?.D || '';
        } else {
            document.getElementById('edit_mcq_fields').classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    </script>
</x-app-layout>