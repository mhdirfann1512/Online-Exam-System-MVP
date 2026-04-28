<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Dashboard Utama
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 border border-black bg-white mb-8">
                <div class="p-4 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-xs font-bold text-black uppercase">Jumlah Peperiksaan</p>
                    <p class="text-xl font-light text-black">{{ $totalExams }}</p>
                </div>
                <div class="p-4 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-xs font-bold text-black uppercase">Jumlah Pelajar</p>
                    <p class="text-xl font-light text-black">{{ $totalStudents }}</p>
                </div>
                <div class="p-4 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-xs font-bold text-black uppercase">Peperiksaan Aktif</p>
                    <p class="text-xl font-light text-black">{{ $ongoingExams }}</p>
                </div>
                <div class="p-4">
                    <p class="text-xs font-bold text-black uppercase">Jumlah Penghantaran</p>
                    <p class="text-xl font-light text-black">{{ $totalSubmissions }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <div class="p-6 bg-white border border-black">
                        <h3 class="mb-6 text-sm font-bold uppercase border-b border-black pb-2">Daftar Peperiksaan Baru</h3>
                        <form action="{{ route('admin.exams.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="title" placeholder="Tajuk Peperiksaan" class="border-black focus:ring-0 focus:border-black text-sm" required>
                                <input type="number" name="duration_minutes" placeholder="Durasi (Minit)" class="border-black focus:ring-0 focus:border-black text-sm" required>
                                <input type="datetime-local" name="start_time" class="border-black focus:ring-0 focus:border-black text-sm" required>
                                <input type="datetime-local" name="end_time" class="border-black focus:ring-0 focus:border-black text-sm" required>
                            </div>
                            <textarea name="instructions" placeholder="Arahan Peperiksaan" class="w-full mt-4 border-black focus:ring-0 focus:border-black text-sm"></textarea>
                            
                            <div class="mt-6">
                                <button type="submit" class="text-sm font-bold uppercase underline hover:no-underline">
                                    [ Simpan Maklumat ]
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="p-6 bg-white border border-black overflow-x-auto">
                        <h3 class="mb-4 text-sm font-bold uppercase">Senarai Peperiksaan Berdaftar</h3>
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-black">
                                    <th class="py-2 text-xs font-bold uppercase">Tajuk</th>
                                    <th class="py-2 text-xs font-bold uppercase">Mula</th>
                                    <th class="py-2 text-xs font-bold uppercase">Tamat</th>
                                    <th class="py-2 text-xs font-bold uppercase text-right">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($exams as $exam)
                                <tr>
                                    <td class="py-3 text-sm">{{ $exam->title }}</td>
                                    <td class="py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y, H:i') }}</td>
                                    <td class="py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y, H:i') }}</td>
                                    <td class="py-3 text-right space-x-4">
                                        <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-sm underline hover:text-gray-600">Urus Soalan</a>
                                        <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-sm font-bold underline hover:text-gray-600">Keputusan</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="p-6 bg-white border border-black">
                        <h3 class="mb-4 text-sm font-bold uppercase border-b border-black pb-2">Pengahntaran Terkini</h3>
                        <div class="space-y-6">
                            @forelse($latestSubmissions as $sub)
                                <div class="pb-2 border-b border-dotted border-gray-400">
                                    <p class="text-sm font-bold uppercase">{{ $sub->user->name }}</p>
                                    <p class="text-xs text-gray-600 italic">{{ $sub->exam->title }}</p>
                                    <div class="flex justify-between mt-1">
                                        <span class="text-xs font-mono">SKOR: {{ $sub->score }}%</span>
                                        <span class="text-[10px] text-gray-400 uppercase">{{ $sub->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 italic">Tiada data penghantaran.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>