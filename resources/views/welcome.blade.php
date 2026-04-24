<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LMS Master') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .bg-pattern {
                background-color: #ffffff;
                background-image: radial-gradient(#4f46e5 0.5px, #ffffff 0.5px);
                background-size: 24px 24px;
                background-opacity: 0.1;
            }
        </style>
    </head>
    <body class="bg-pattern antialiased min-h-screen flex flex-col items-center justify-center p-6">
        
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] rounded-full bg-indigo-100/50 blur-[120px]"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] rounded-full bg-rose-100/50 blur-[120px]"></div>
        </div>

        <div class="w-full max-w-5xl">
            <nav class="flex justify-between items-center mb-16">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="text-xl font-extrabold text-slate-800 tracking-tight">LMS<span class="text-indigo-600">PRO</span></span>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-bold shadow-xl shadow-slate-200 hover:scale-105 transition-transform">Buka Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-slate-600 text-sm font-bold hover:text-indigo-600 transition-colors">Log Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all hover:shadow-indigo-200">Daftar Sekarang</a>
                        @endif
                    @endauth
                </div>
            </nav>

            <main class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest mb-6">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Sistem Peperiksaan Digital v2.0
                    </div>
                    <h1 class="text-5xl lg:text-7xl font-black text-slate-900 leading-[1.1] mb-6 tracking-tighter">
                        Urus Peperiksaan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-violet-600">Lebih Pantas.</span>
                    </h1>
                    <p class="text-lg text-slate-500 leading-relaxed mb-10 max-w-lg mx-auto lg:mx-0">
                        Platform pengurusan soalan dan peperiksaan yang direka untuk memudahkan pendidik dan memperkasakan pelajar.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-slate-900 text-white rounded-2xl font-black shadow-2xl shadow-slate-300 hover:-translate-y-1 transition-all">
                            Mula Sekarang
                        </a>
                        <div class="flex -space-x-3">
                            @for($i=1; $i<=4; $i++)
                                <div class="w-10 h-10 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center overflow-hidden">
                                    <img src="https://i.pravatar.cc/100?img={{$i+10}}" alt="User">
                                </div>
                            @endfor
                            <div class="pl-5 text-xs font-bold text-slate-400 flex items-center">
                                +200 Users Joined
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="relative z-10 bg-white/40 backdrop-blur-xl border border-white/60 p-4 rounded-[2.5rem] shadow-2xl shadow-indigo-100">
                        <div class="bg-white rounded-[2rem] overflow-hidden shadow-inner">
                             <img src="https://images.unsplash.com/photo-1434030216411-0b793f4b4173?auto=format&fit=crop&w=800&q=80" class="w-full h-auto" alt="LMS Preview">
                        </div>
                    </div>
                    <div class="absolute -top-6 -right-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-50 animate-bounce transition-all duration-[3000ms]">
                        <p class="text-[10px] font-black text-slate-400 uppercase">Success Rate</p>
                        <p class="text-xl font-black text-emerald-500">99.9%</p>
                    </div>
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-50">
                        <p class="text-[10px] font-black text-slate-400 uppercase">Questions Bank</p>
                        <p class="text-xl font-black text-indigo-600">1.2k+</p>
                    </div>
                </div>
            </main>

            <footer class="mt-20 py-10 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 text-slate-400 text-xs font-bold uppercase tracking-widest">
                <p>&copy; 2026 {{ config('app.name') }}. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-indigo-600 transition-colors">Documentation</a>
                    <a href="#" class="hover:text-indigo-600 transition-colors">Privacy Policy</a>
                </div>
            </footer>
        </div>
    </body>
</html>