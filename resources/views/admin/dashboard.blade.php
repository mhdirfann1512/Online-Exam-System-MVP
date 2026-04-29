<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Dashboard Utama SIUUUUUU
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-4 border border-black bg-white mb-8">
                <div class="p-6 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Jumlah Peperiksaan</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $totalExams }}</p>
                </div>
                <div class="p-6 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Jumlah Pelajar</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $totalStudents }}</p>
                </div>
                <div class="p-6 border-b md:border-b-0 md:border-r border-black">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Peperiksaan Aktif</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $ongoingExams }}</p>
                </div>
                <div class="p-6">
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em] mb-1">Jumlah Penghantaran</p>
                    <p class="text-4xl font-bold text-black font-mono">{{ $totalSubmissions }}</p>
                </div>
            </div>

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
</x-app-layout>