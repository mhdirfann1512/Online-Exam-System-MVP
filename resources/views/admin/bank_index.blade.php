<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Pusat Bank Soalan (Master Bank)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <p class="text-gray-600">Di sini anda boleh memuat turun soalan daripada semua peperiksaan yang telah dicipta.</p>
                </div>

                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left text-sm uppercase">
                            <th class="p-3 border">Tajuk Peperiksaan</th>
                            <th class="p-3 border text-center">Bil. Soalan</th>
                            <th class="p-3 border">Tarikh Mula</th>
                            <th class="p-3 border text-center">Tindakan Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border font-medium">{{ $exam->title }}</td>
                            <td class="p-3 border text-center">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $exam->questions_count }} Soalan
                                </span>
                            </td>
                            <td class="p-3 border text-sm">{{ $exam->start_time }}</td>
                            <td class="p-3 border text-center">
                                <div class="flex justify-center space-x-2">
                                    {{-- Link Export Excel --}}
                                    <a href="{{ route('admin.exams.export-excel', $exam->id) }}" 
                                       class="bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-1.5 px-3 rounded flex items-center shadow-sm transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        EXCEL
                                    </a>

                                    {{-- Link Export PDF --}}
                                    <a href="{{ route('admin.exams.export-pdf', $exam->id) }}" 
                                       class="bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-1.5 px-3 rounded flex items-center shadow-sm transition">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>