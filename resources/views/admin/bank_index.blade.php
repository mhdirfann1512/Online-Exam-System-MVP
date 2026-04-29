<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            {{ __('Pusat Bank Soalan (Master Bank)') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-black p-6">
                
                <div class="mb-6 pb-4 border-b border-black">
                    <p class="text-xs uppercase font-bold text-black tracking-widest">Maklumat:</p>
                    <p class="text-sm text-gray-700 italic">Di sini anda boleh memuat turun soalan daripada semua peperiksaan yang telah dicipta untuk tujuan arkib dan rekod.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-black">
                        <thead>
                            <tr class="bg-black text-white text-center text-xs uppercase tracking-tighter">
                                <th class="p-3 border border-black font-bold">Tajuk Peperiksaan</th>
                                <th class="p-3 border border-black text-center font-bold">Bil. Soalan</th>
                                <th class="p-3 border border-black font-bold">Tarikh Daftar Peperiksaan</th>
                                <th class="p-3 border border-black text-center font-bold">Muat Turun Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black">
                            @foreach($exams as $exam)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="p-3 border border-black text-sm font-bold uppercase">{{ $exam->title }}</td>
                                <td class="p-3 border border-black text-center text-sm font-mono">
                                    [{{ $exam->questions_count }} UNIT]
                                </td>
                                <td class="p-3 border border-black text-xs font-mono text-gray-600">
                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y H:i') }}
                                </td>
                                <td class="p-3 border border-black text-center">
                                    <div class="flex justify-center space-x-6">
                                        {{-- Link Export Excel --}}
                                        <a href="{{ route('admin.exams.export-excel', $exam->id) }}" 
                                           class="text-xs font-bold underline uppercase hover:text-gray-500">
                                            [ EXCEL ]
                                        </a>

                                        {{-- Link Export PDF --}}
                                        <a href="{{ route('admin.exams.export-pdf', $exam->id) }}" 
                                           class="text-xs font-bold underline uppercase hover:text-gray-500">
                                            [ PDF ]
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    <p class="text-[10px] text-gray-400 uppercase tracking-tighter">
                        * Dokumen dijana secara automatik oleh sistem.
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>