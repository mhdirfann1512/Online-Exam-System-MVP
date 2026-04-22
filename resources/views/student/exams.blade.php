<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Peperiksaan') }}
        </h2>
    </x-slot>

    @if(session('success'))
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded" role="alert">
            <p class="font-bold">Tahniah!</p>
            <p>{{ session('success') }}</p>
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
                                        <span class="font-semibold w-24">Ends at:</span>
                                        <span class="text-red-500">{{ \Carbon\Carbon::parse($exam->end_time)->format('d M, h:i A') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-left mt-4">
                            @if($now->lt($exam->start_time))
                                <button class="bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed" disabled>
                                    Belum Mula ({{ \Carbon\Carbon::parse($exam->start_time)->format('h:i A') }})
                                </button>
                            @else
                                <a href="{{ route('student.exams.show', $exam->id) }}" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">
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