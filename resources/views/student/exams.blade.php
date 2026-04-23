<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Peperiksaan') }}
        </h2>
    </x-slot>

@if(session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded-lg" role="alert">
            <p class="font-bold text-lg text-green-800">Berjaya!</p>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 shadow-sm rounded">
            <p>{{ session('error') }}</p>
        </div>
    </div>
@endif

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 mb-6 bg-white shadow-sm sm:rounded-lg">
                <h3 class="mb-4 text-lg font-bold">List of Active Exams</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($exams as $exam)
                        <div class="flex flex-col justify-between p-6 bg-gray-50 border border-gray-200 rounded-xl hover:shadow-md transition duration-200">
                            <div>
                                <h4 class="text-xl font-extrabold text-indigo-700 mb-2">{{ $exam->title }}</h4>
                                
                                <div class="space-y-2 mb-6">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="font-semibold w-24">Duration:</span>
                                        <span>{{ $exam->duration_minutes }} Minutes</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="font-semibold w-24">Start at:</span>
                                        <span class="text-red-500">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M, h:i A') }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="font-semibold w-24">Ends at:</span>
                                        <span class="text-red-500">{{ \Carbon\Carbon::parse($exam->end_time)->format('d M, h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            
<div class="flex justify-left mt-4">
    {{-- 1. Check kalau student DAH pernah jawab exam ni --}}
    @if(in_array($exam->id, $userSubmissions))
        @if($exam->is_published)
            {{-- Kalau dah publish, tunjuk butang result --}}
            <a href="{{ route('student.results.show', $exam->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow-sm transition">
                Lihat Keputusan
            </a>
        @else
            {{-- Kalau belum publish, tunjuk status sahaja --}}
            <button class="bg-green-100 text-green-700 border border-green-200 px-6 py-2 rounded-lg font-bold cursor-default" disabled>
                Selesai Dihantar
            </button>
            <p class="ml-3 text-xs text-gray-500 self-center italic">Keputusan belum diterbitkan</p>
        @endif

    {{-- 2. Check kalau student BELUM jawab & exam BELUM mula --}}
    @elseif($now->lt($exam->start_time))
        <button class="bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed" disabled>
            Belum Mula ({{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }})
        </button>

    {{-- 3. Check kalau student BELUM jawab & exam DAH tamat (IDLE) --}}
    @elseif($now->gt($exam->end_time))
        <button class="bg-red-100 text-red-600 border border-red-200 px-6 py-2 rounded-lg font-bold cursor-not-allowed" disabled>
            Telah Tamat
        </button>

    {{-- 4. Jika exam sedang berlangsung & student belum jawab --}}
    @else
        <a href="{{ route('student.exams.show', $exam->id) }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold transition">
            Start Exam
        </a>
    @endif
</div>
                        </div>
                    @empty
                        <div class="col-span-full py-10 text-center">
                            <div class="text-gray-400 text-5xl mb-4">📅</div>
                            <p class="text-gray-500 font-medium text-lg">No exams available at the moment.</p>
                            <p class="text-gray-400 text-sm">Please check back later or contact your lecturer.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>