<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Bank Soalan: Pilih Untuk Exam {{ $exam->title ?? '' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6">
                <p class="text-xs font-bold uppercase text-gray-600 tracking-widest border-b border-black pb-1 inline-block">
                    Senarai Rekod Peperiksaan Tersedia
                </p>
            </div>

            @foreach($allExams as $ex)
                <div class="mb-4 bg-white border border-black overflow-hidden">
                    <div class="p-4 bg-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center border-b border-black gap-4">
                        <div>
                            <span class="font-bold text-sm uppercase tracking-tighter text-black">{{ $ex->title }}</span>
                            <span class="ml-2 text-[10px] font-mono text-gray-500 italic">[{{ $ex->questions_count }} UNIT SOALAN]</span>
                        </div>
                        
                        <div class="flex items-center gap-6">
                            <form action="{{ route('admin.questions.copy_exam', $targetExamId) }}" method="POST" class="inline">
                                @csrf
                                <input type="hidden" name="source_exam_id" value="{{ $ex->id }}">
                                <button type="submit" class="text-xs font-bold uppercase underline hover:no-underline">
                                    [ SALIN KESELURUHAN SET ]
                                </button>
                            </form>

                            <button onclick="toggleExam({{ $ex->id }})" class="text-xs font-bold uppercase underline hover:no-underline text-black">
                                [ LIHAT & PILIH UNIT ]
                            </button>
                        </div>
                    </div>

                    <div id="exam-list-{{ $ex->id }}" class="hidden p-0 bg-white">
                        <form action="{{ route('admin.questions.copy_selected', $targetExamId) }}" method="POST">
                            @csrf
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-black text-white text-[10px] uppercase">
                                    <tr>
                                        <th class="p-2 border border-black w-10 text-center">PILIH</th>
                                        <th class="p-2 border border-black">JENIS</th>
                                        <th class="p-2 border border-black">PERNYATAAN SOALAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($ex->questions as $q)
                                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                        <td class="p-3 border-x border-black text-center">
                                            <input type="checkbox" name="question_ids[]" value="{{ $q->id }}" class="w-4 h-4 border-black text-black focus:ring-0">
                                        </td>
                                        <td class="p-3 border-r border-black text-[10px] font-mono uppercase font-bold text-gray-600">
                                            {{ $q->type }}
                                        </td>
                                        <td class="p-3 text-sm font-medium text-black">
                                            {{ $q->question_text }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            <div class="p-4 bg-gray-100 border-t border-black flex justify-end">
                                <button type="submit" class="text-xs font-bold uppercase underline hover:no-underline tracking-widest">
                                    [ + TAMBAH UNIT YANG DIPILIH KE DALAM EXAM ]
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="mt-8 pt-4 border-t border-dotted border-gray-400 text-right">
                <a href="{{ url()->previous() }}" class="text-[10px] font-bold uppercase underline hover:text-gray-500">
                    << Kembali ke Pengurusan Soalan
                </a>
            </div>

        </div>
    </div>

    <script>
        function toggleExam(id) {
            const element = document.getElementById('exam-list-' + id);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>