<x-guest-layout>
    <div class="mb-10 text-center">
        <h2 class="text-3xl font-black text-black tracking-tighter uppercase italic">
            CREATE_ACCOUNT
        </h2>
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mt-1">
            New_Identity_Provisioning
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <div>
            <label for="name" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                // FULL_NAME_STRING
            </label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   :value="old('name')" 
                   required autofocus 
                   class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300 uppercase"
                   placeholder="JOHN_DOE" />
            <x-input-error :messages="$errors->get('name')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />
        </div>

        <div>
            <label for="email" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                // SYSTEM_EMAIL
            </label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   required 
                   class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300"
                   placeholder="USER@SERVER.COM" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                    // KEY_PASS
                </label>
                <input id="password" 
                       type="password"
                       name="password"
                       required 
                       class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300"
                       placeholder="••••••••" />
            </div>

            <div>
                <label for="password_confirmation" class="block text-[10px] font-black text-black uppercase tracking-widest mb-1 ml-1 italic">
                    // VERIFY_KEY
                </label>
                <input id="password_confirmation" 
                       type="password"
                       name="password_confirmation" 
                       required 
                       class="block w-full border-4 border-black p-4 text-sm font-bold focus:ring-0 focus:border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] transition-all placeholder:text-slate-300"
                       placeholder="••••••••" />
            </div>
        </div>
        <x-input-error :messages="$errors->get('password')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 font-bold text-red-600 uppercase text-[10px]" />

        <div class="flex items-center justify-between pt-4">
            <a class="text-[10px] font-black text-slate-400 uppercase tracking-tighter hover:text-black transition-colors underline decoration-2 underline-offset-4" href="{{ route('login') }}">
                {{ __('Already_Known_User?') }}
            </a>

            <button type="submit" class="px-8 py-4 bg-black text-yellow-400 border-4 border-black font-black text-sm uppercase tracking-widest shadow-[8px_8px_0px_0px_rgba(0,0,0,0.2)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                ENROLL_IDENTITY >>
            </button>
        </div>
    </form>

    <div class="mt-10 pt-6 border-t-2 border-black border-dashed text-center">
        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">
            All data will be stored in encrypted server nodes
        </p>
    </div>
</x-guest-layout>