<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LMS PRO') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:800" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .bg-grid {
                background-color: #e5e5e5;
                background-image: radial-gradient(#000000 0.5px, transparent 0.5px);
                background-size: 30px 30px;
            }
        </style>
    </head>
    <body class="bg-grid antialiased min-h-screen flex flex-col items-center justify-center p-6 font-mono">
        
        <div class="w-full max-w-6xl">
            <nav class="flex justify-between items-center mb-20 bg-white border-4 border-black p-4 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-black flex items-center justify-center text-yellow-400 border-2 border-black">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-black tracking-tighter italic uppercase">LMS_PRO_v2</span>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2 bg-black text-white text-xs font-black uppercase tracking-widest hover:bg-yellow-400 hover:text-black transition-colors border-2 border-black">Dashboard_Entry</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-black text-xs font-black uppercase tracking-widest hover:underline">Log_In</a>
                        <a href="{{ route('register') }}" class="px-6 py-2 bg-yellow-400 text-black border-2 border-black text-xs font-black uppercase tracking-widest shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] hover:shadow-none hover:translate-x-1 hover:translate-y-1 transition-all">Register_System</a>
                    @endauth
                </div>
            </nav>

            <main class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-block px-4 py-1 bg-black text-emerald-400 text-[10px] font-black uppercase tracking-[0.3em] mb-8">
                        Status: System_Online // 2026
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black text-black leading-[0.9] mb-8 tracking-tighter uppercase italic">
                        MANAGE_EXAMS<br>
                        <span class="bg-black text-white px-2">FASTER.</span>
                    </h1>
                    
                    <p class="text-lg text-black font-bold leading-tight mb-12 max-w-md border-l-4 border-black pl-6 italic">
                        Terminal pengurusan peperiksaan digital yang direka untuk kestabilan tinggi dan kawalan penuh pendidik.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-5 bg-black text-yellow-400 text-xl font-black uppercase tracking-tighter shadow-[10px_10px_0px_0px_rgba(0,0,0,0.2)] hover:shadow-none hover:translate-x-2 hover:translate-y-2 transition-all border-2 border-black">
                            INITIALIZE_START
                        </a>
                        
                        <div class="flex flex-col items-start gap-1">
                             <div class="flex -space-x-2">
                                @for($i=1; $i<=3; $i++)
                                    <div class="w-8 h-8 rounded-none border-2 border-black bg-white overflow-hidden">
                                        <img src="https://i.pravatar.cc/100?img={{$i+20}}" alt="User">
                                    </div>
                                @endfor
                            </div>
                            <span class="text-[9px] font-black text-black uppercase tracking-widest mt-1">200+_Nodes_Active</span>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="border-8 border-black bg-white shadow-[20px_20px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
                         <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80" class="w-full h-auto grayscale contrast-125" alt="LMS Preview">
                    </div>
                    
                    <div class="absolute -top-6 -right-6 bg-emerald-400 border-4 border-black p-4 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <p class="text-[9px] font-black text-black uppercase">Uptime_Rate</p>
                        <p class="text-2xl font-black text-black">99.9%</p>
                    </div>
                    
                    <div class="absolute -bottom-6 -left-6 bg-yellow-400 border-4 border-black p-4 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
                        <p class="text-[9px] font-black text-black uppercase">Database_Ready</p>
                        <p class="text-2xl font-black text-black">1.2k+</p>
                    </div>
                </div>
            </main>

            <footer class="mt-32 py-8 border-t-4 border-black flex flex-col md:flex-row justify-between items-center gap-4 text-black text-[10px] font-black uppercase tracking-[0.2em]">
                <p>&copy; 2026 // {{ config('app.name') }} // ALL_RIGHTS_RESERVED</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:bg-black hover:text-white px-2 py-1 transition-colors">Documentation</a>
                    <a href="#" class="hover:bg-black hover:text-white px-2 py-1 transition-colors">Core_Privacy</a>
                </div>
            </footer>
        </div>
    </body>
</html>