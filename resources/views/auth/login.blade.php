<x-guest-layout>
    <div class="mb-10 border-l border-black pl-4">
        <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Log Masuk</h2>
        <p class="text-[10px] text-gray-500 uppercase mt-1 text-xs">Sila masukkan kredential rasmi untuk sesi audit</p>
    </div>

    <x-auth-session-status class="mb-4 text-xs font-bold uppercase" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">ID_PENGGUNA / EMAIL</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   required 
                   autofocus 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none placeholder-gray-300"
                   placeholder="user@lms.local" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div>
            <label for="password" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">KATA_LALUAN</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div class="flex flex-col gap-3">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" 
                       type="checkbox" 
                       class="rounded-none border-black text-black shadow-none focus:ring-0 w-3 h-3" 
                       name="remember">
                <span class="ms-2 text-[10px] font-bold uppercase text-black italic-none">Kekalkan Sesi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[10px] font-bold uppercase text-gray-400 hover:text-black underline underline-offset-4" href="{{ route('password.request') }}">
                    Tukar Kata Laluan?
                </a>
            @endif
        </div>

        <div class="pt-4 border-t border-black border-dotted flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-[10px] font-bold uppercase text-black hover:underline tracking-widest">
                << KEMBALI
            </a>
            
            <button type="submit" class="text-xs font-bold uppercase tracking-widest text-black hover:bg-black hover:text-white px-4 py-2 transition-all">
                [ LOG_MASUK ]
            </button>
        </div>
    </form>
</x-guest-layout>