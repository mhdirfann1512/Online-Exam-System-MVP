<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Laporan Keputusan: {{ $exam->title }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white border border-black p-6">
                
                <div class="overflow-x-auto mb-8">
                    <table class="w-full border-collapse border border-black text-left">
                        <thead>
                            <tr class="bg-black text-white text-[10px] uppercase tracking-widest">
                                <th class="p-3 border border-black font-bold">Nama Pelajar</th>
                                <th class="p-3 border border-black text-center font-bold">Skor (Unit)</th>
                                <th class="p-3 border border-black text-center font-bold">Peratus (%)</th>
                                <th class="p-3 border border-black text-center font-bold">Tarikh/Masa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black">
                            @foreach($submissions as $s)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 border border-black text-sm font-bold uppercase">{{ $s->user->name }}</td>
                                <td class="p-3 border border-black text-center">
                                    <form action="{{ route('admin.submissions.update-score', $s->id) }}" method="POST" class="flex items-center justify-center space-x-2">
                                        @csrf
                                        <input type="number" 
                                            name="new_correct" 
                                            value="{{ $s->correct_answers }}" 
                                            class="w-14 p-1 text-xs text-center border border-black focus:ring-0 focus:border-black font-mono"
                                            min="0" 
                                            max="{{ $s->total_questions }}">
                                        
                                        <span class="text-[10px] font-bold text-gray-500 font-mono">/ {{ $s->total_questions }}</span>
                                        
                                        <button type="submit" onclick="return confirm('KEMASKINI DATA: Teruskan?')" class="text-[10px] font-bold underline uppercase ml-2">
                                            [ KEMASKINI ]
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3 border border-black text-center font-mono text-sm">
                                    <span class="{{ $s->score >= 50 ? 'text-black' : 'text-gray-500 italic' }}">
                                        {{ number_format($s->score, 2) }}%
                                    </span>
                                </td>
                                <td class="p-3 border border-black text-center text-[10px] font-mono text-gray-600">
                                    {{ $s->created_at->format('d-m-Y | H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center pt-6 border-t border-black border-dashed gap-4">
                    <form action="{{ route('admin.exams.publish', $exam->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-bold uppercase underline hover:no-underline tracking-tighter">
                            @if($exam->is_published)
                                [ ! NYAH-TERBIT KEPUTUSAN ]
                            @else
                                [ + TERBITKAN KEPUTUSAN RASMI ]
                            @endif
                        </button>
                    </form>

                    <a href="{{ route('admin.dashboard') }}" class="text-[10px] font-bold uppercase underline hover:text-gray-500">
                        << Kembali ke Dashboard Utama
                    </a>
                </div>

                <div class="mt-8">
                    <p class="text-[9px] text-gray-400 uppercase italic">
                        * Data ini dijana secara automatik. Sebarang pindaan manual akan direkodkan dalam log sistem.
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>