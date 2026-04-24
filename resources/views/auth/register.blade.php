<x-guest-layout>
    <div class="mb-10 border-l border-black pl-4">
        <h2 class="text-xs font-bold uppercase tracking-[0.2em]">Pendaftaran Akaun Baru</h2>
        <p class="text-[10px] text-gray-500 uppercase mt-1">Sila lengkapkan maklumat profil bagi akses sistem</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">NAMA_PENUH</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   :value="old('name')" 
                   required 
                   autofocus 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none placeholder-gray-300"
                   placeholder="NAMA ANDA" />
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div>
            <label for="email" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">ALAMAT_EMAIL</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   :value="old('email')" 
                   required 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none placeholder-gray-300"
                   placeholder="user@lms.local" />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div>
            <label for="password" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">KATA_LALUAN_BARU</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none" />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold uppercase text-gray-500 mb-1">PENGESAHAN_KATA_LALUAN</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   class="w-full bg-transparent border border-black p-2 text-sm focus:ring-0 focus:outline-none rounded-none" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[10px] uppercase font-bold text-red-600" />
        </div>

        <div class="pt-6 border-t border-black border-dotted flex items-center justify-between">
            <a class="text-[10px] font-bold uppercase text-gray-400 hover:text-black underline underline-offset-4" href="{{ route('login') }}">
                DAH ADA AKAUN?
            </a>

            <button type="submit" class="text-xs font-bold uppercase tracking-widest text-black hover:bg-black hover:text-white px-4 py-2 transition-all">
                [ DAFTAR_PROFIL ]
            </button>
        </div>
    </form>
</x-guest-layout>