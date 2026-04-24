<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Mini LMS | Online Exam System</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#FDFDFC] text-black font-sans antialiased min-h-screen flex flex-col">
        
        <nav class="w-full border-b border-black px-6 py-3 flex justify-between items-center">
            <div class="text-sm font-bold uppercase tracking-widest">
                [ MINI_LMS_SYSTEM ]
            </div>
            
            <div class="flex gap-6">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-bold uppercase underline decoration-1 underline-offset-4">
                            DASHBOARD
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase underline decoration-1 underline-offset-4">
                            LOGIN
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-xs font-bold uppercase underline decoration-1 underline-offset-4">
                                REGISTER
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </nav>

        <main class="flex-1 flex flex-col items-center justify-center p-6">
            <div class="w-full max-w-4xl">
                <div class="mb-8 border-l border-black pl-4">
                    <span class="text-[10px] text-gray-500 uppercase block mb-1">Project Status</span>
                    <span class="text-xs font-bold uppercase">MVP_VERSION_1.0 // ACTIVE</span>
                </div>

                <div class="mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold uppercase tracking-tighter mb-4 leading-none">
                        ONLINE EXAM SYSTEM.
                    </h1>
                    <p class="text-sm text-gray-600 max-w-xl leading-relaxed uppercase">
                        Sistem pengurusan pembelajaran ringkas untuk pengendalian peperiksaan digital, 
                        pemarkahan automatik, dan analisis data pelajar secara statis.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 border-t border-black pt-10">
                    <div>
                        <h3 class="text-xs font-bold uppercase mb-4 tracking-wider text-gray-400">01 / Pelajar</h3>
                        <p class="text-xs leading-relaxed uppercase mb-4">
                            Akses modul peperiksaan yang telah ditetapkan, jawab soalan dalam persekitaran minimalis, dan terima keputusan serta-merta.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-xs font-bold uppercase mb-4 tracking-wider text-gray-400">02 / Pendidik</h3>
                        <p class="text-xs leading-relaxed uppercase mb-4">
                            Kawalan penuh ke atas bank soalan, pemantauan status penyerahan, dan pengurusan gred tanpa elemen luaran yang tidak perlu.
                        </p>
                    </div>
                </div>
            </div>
        </main>

        <footer class="w-full border-t border-black p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                System_Release: 2026.04.24
            </div>
            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                &copy; {{ date('Y') }} ALL_RIGHTS_RESERVED
            </div>
        </footer>

    </body>
</html>