<nav x-data="{ open: false }" class="bg-white border-b-4 border-black font-mono">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-2">
                        <div class="w-10 h-10 bg-black flex items-center justify-center text-yellow-400 border-2 border-black group-hover:bg-yellow-400 group-hover:text-black transition-colors">
                             <span class="font-black text-xl">L</span>
                        </div>
                        <span class="text-xl font-black text-black tracking-tighter uppercase italic hidden md:block">LMS_PRO_v2</span>
                    </a>
                </div>

                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="px-4 py-2 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('dashboard') ? 'bg-black text-white' : 'text-black hover:bg-black hover:text-white' }}">
                        [ DASHBOARD ]
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.bank.index') }}" 
                           class="px-4 py-2 text-xs font-black uppercase tracking-widest transition-all {{ request()->routeIs('admin.bank.index') ? 'bg-black text-white' : 'text-black hover:bg-black hover:text-white' }}">
                            [ BANK_SOALAN ]
                        </a>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                    <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 border-2 border-black bg-white shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">
                        <div class="text-[10px] font-black uppercase tracking-tighter text-black">
                            USER://{{ Auth::user()->name }}
                        </div>
                        <svg class="w-4 h-4 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 bg-white border-4 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] z-50">
                        
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest text-black hover:bg-yellow-400 border-b-2 border-black">
                            EDIT_PROFILE
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-4 py-3 text-xs font-black uppercase tracking-widest text-red-600 hover:bg-black hover:text-white transition-colors">
                                TERMINATE_SESSION
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 border-2 border-black bg-black text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t-4 border-black bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('dashboard') ? 'bg-black text-white' : 'text-black hover:bg-yellow-400' }}">
                DASHBOARD
            </a>
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.bank.index') }}" class="block px-4 py-3 text-sm font-black uppercase tracking-widest {{ request()->routeIs('admin.bank.index') ? 'bg-black text-white' : 'text-black hover:bg-yellow-400' }}">
                    BANK_SOALAN
                </a>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t-4 border-black">
            <div class="px-4 mb-4">
                <div class="font-black text-base text-black uppercase underline italic">{{ Auth::user()->name }}</div>
                <div class="font-bold text-[10px] text-slate-500 uppercase tracking-widest">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-xs font-black uppercase tracking-widest text-black hover:bg-yellow-400">
                    EDIT_PROFILE
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-3 text-xs font-black uppercase tracking-widest text-red-600 hover:bg-black hover:text-white">
                        TERMINATE_SESSION
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>