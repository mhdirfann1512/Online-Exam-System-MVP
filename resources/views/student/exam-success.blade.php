<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center">
                <div class="flex justify-center mb-6">
                    <div class="bg-green-100 p-4 rounded-full">
                        <svg class="w-16 h-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-800 mb-2">Peperiksaan Selesai!</h2>
                <p class="text-gray-600 mb-8">
                    Tahniah, jawapan anda telah berjaya dihantar ke dalam sistem. 
                    Keputusan akan dimaklumkan oleh admin/guru setelah proses penilaian selesai.
                </p>

                <div class="flex justify-center gap-4">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>