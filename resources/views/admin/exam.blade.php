<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold leading-tight text-black uppercase tracking-tight">
            Pengurusan Peperiksaan
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">
            
            <div class="p-6 bg-white border border-black">
                <h3 class="mb-6 text-sm font-bold uppercase border-b border-black pb-2">Daftar Peperiksaan Baru</h3>
                <form action="{{ route('admin.exams.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

            <div class="p-6 bg-white border border-black overflow-x-auto" x-data="{ search: '' }">                
                <h3 class="mb-4 text-sm font-bold uppercase">Senarai Peperiksaan Berdaftar</h3>

                <table class="w-full text-left border-collapse">

                    <div class="mb-4 relative">
                        <input type="text" 
                            x-model="search" 
                            placeholder="Cari tajuk peperiksaan..." 
                            class="border-black focus:ring-0 focus:border-black text-xs w-64 uppercase">
                    </div>
                
                    <thead>
                        <tr class="bg-black text-white text-center text-xs uppercase tracking-tighter">                                   
                            <th class="p-3 border border-black font-bold text-left">Tajuk</th>
                            <th class="p-3 border border-black font-bold">Mula</th>
                            <th class="p-3 border border-black font-bold">Tamat</th>
                            <th class="p-3 border border-black font-bold">Tindakan</th>
                            <th class="p-3 border border-black font-bold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($exams as $exam)
                        <tr x-show="'{{ strtoupper($exam->title) }}'.includes(search.toUpperCase())" 
                            x-data="{ openEdit: false }">
                        
                            <td class="py-3 text-sm uppercase px-2 font-bold">{{ $exam->title }}</td>
                            <td class="py-3 text-sm text-gray-600 text-center">{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y, H:i') }}</td>
                            <td class="py-3 text-sm text-gray-600 text-center">{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y, H:i') }}</td>
                            <td class="py-3 text-center space-x-4 px-2">
                                <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-sm underline hover:text-gray-600">Urus Soalan</a>
                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-sm font-bold underline hover:text-gray-600">Keputusan</a>
                            </td>
                            <td class="py-3 text-right space-x-4 px-2">
                                <button @click="openEdit = true" class="text-sm text-blue-600 underline hover:text-blue-800">Edit</button>

                                <form action="{{ route('admin.exams.destroy', $exam->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('AMARAN: Memadam peperiksaan ini akan memadam semua soalan dan rekod markah pelajar. Teruskan?')"
                                            class="text-sm text-red-600 underline hover:text-red-800">
                                        Padam
                                    </button>
                                </form>

                                <div x-show="openEdit" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
                                    <div class="flex items-center justify-center min-h-screen px-4">
                                        <div class="fixed inset-0 bg-black opacity-50"></div>
                                        <div class="relative bg-white border border-black p-8 w-full max-w-lg shadow-xl">
                                            <h3 class="text-lg font-bold uppercase mb-4 border-b border-black pb-2 text-left">Kemaskini Peperiksaan</h3>
                                            
                                            <form action="{{ route('admin.exams.update', $exam->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="grid grid-cols-1 gap-4 text-left">
                                                    <div>
                                                        <label class="text-xs font-bold uppercase">Tajuk</label>
                                                        <input type="text" name="title" value="{{ $exam->title }}" class="w-full border-black focus:ring-0 text-sm" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold uppercase">Durasi (Minit)</label>
                                                        <input type="number" name="duration_minutes" value="{{ $exam->duration_minutes }}" class="w-full border-black focus:ring-0 text-sm" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold uppercase">Mula</label>
                                                        <input type="datetime-local" name="start_time" value="{{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') }}" class="w-full border-black focus:ring-0 text-sm" required>
                                                    </div>
                                                    <div>
                                                        <label class="text-xs font-bold uppercase">Tamat</label>
                                                        <input type="datetime-local" name="end_time" value="{{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') }}" class="w-full border-black focus:ring-0 text-sm" required>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end space-x-4">
                                                    <button type="button" @click="openEdit = false" class="text-xs font-bold uppercase underline">Batal</button>
                                                    <button type="submit" class="text-xs font-bold uppercase bg-black text-white px-4 py-2 italic">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div x-show="search !== '' && !Array.from($el.querySelectorAll('tbody tr')).some(tr => tr.style.display !== 'none')" 
                    class="py-4 text-center text-xs text-gray-500 italic">
                    Tiada peperiksaan padan dengan "{{ strtoupper('search') }}"
                </div>

            </div>

        </div>
    </div>
</x-app-layout>