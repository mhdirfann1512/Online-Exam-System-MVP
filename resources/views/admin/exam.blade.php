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

            <div class="p-6 bg-white border border-black overflow-x-auto">
                <h3 class="mb-4 text-sm font-bold uppercase">Senarai Peperiksaan Berdaftar</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-black text-white text-center text-xs uppercase tracking-tighter">                                   
                            <th class="p-3 border border-black font-bold text-left">Tajuk</th>
                            <th class="p-3 border border-black font-bold">Mula</th>
                            <th class="p-3 border border-black font-bold">Tamat</th>
                            <th class="p-3 border border-black font-bold">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($exams as $exam)
                        <tr>
                            <td class="py-3 text-sm uppercase px-2 font-bold">{{ $exam->title }}</td>
                            <td class="py-3 text-sm text-gray-600 text-center">{{ \Carbon\Carbon::parse($exam->start_time)->format('d/m/Y, H:i') }}</td>
                            <td class="py-3 text-sm text-gray-600 text-center">{{ \Carbon\Carbon::parse($exam->end_time)->format('d/m/Y, H:i') }}</td>
                            <td class="py-3 text-right space-x-4 px-2">
                                <a href="{{ route('admin.questions.index', $exam->id) }}" class="text-sm underline hover:text-gray-600">Urus Soalan</a>
                                <a href="{{ route('admin.exams.results', $exam->id) }}" class="text-sm font-bold underline hover:text-gray-600">Keputusan</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>