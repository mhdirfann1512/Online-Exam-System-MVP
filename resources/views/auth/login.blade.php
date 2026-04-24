<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-black tracking-tighter uppercase italic">
            SYSTEM_ACCESS
        </h2>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">
            Secure Entry Protocol v2.0
        </p>
    </div>

    <x-auth-session-status class="mb-4 font-bold text-emerald-600 bg-emerald-50 p-3 border-2 border-emerald-600" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div class="group">
            <label for="email" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                // USER_IDENTIFICATION
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   required autofocus 
                   class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300"
                   placeholder="NAME@SERVER.COM" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />
        </div>

        <div>
            <label for="password" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                // SECURITY_KEY_ID
            </label>
            <input id="password" 
                   type="password"
                   name="password"
                   required 
                   class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300"
                   placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="w-5 h-5 border-2 border-black text-black focus:ring-0 rounded-none" name="remember">
                <span class="ms-2 text-[10px] font-black text-black uppercase tracking-tighter">{{ __('Keep_Session_Active') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-[10px] font-black text-slate-400 uppercase tracking-tighter hover:text-black transition-colors" href="{{ route('password.request') }}">
                    {{ __('Lost_Credential?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full bg-black text-yellow-400 py-4 border-4 border-black font-black text-sm uppercase tracking-widest shadow-[8px_8px_0px_0px_rgba(0,0,0,0.2)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all active:bg-yellow-400 active:text-black">
                GRANT_ACCESS >>
            </button>
        </div>
    </form>

    <div class="mt-10 pt-6 border-t-2 border-black border-dashed text-center">
        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">
            Authorized Personnel Only
        </p>
    </div>
</x-guest-layout>