<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perseus Safety and Security Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen overflow-hidden bg-[#eef3f9] text-slate-800">
    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-[300px] shrink-0 bg-[linear-gradient(180deg,#08111f_0%,#0b1728_45%,#0f1d31_100%)] text-white border-r border-white/5 shadow-2xl flex flex-col">
            <div class="px-6 py-6 border-b border-white/10">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center overflow-hidden shadow-lg">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-11 w-11 object-contain">
                    </div>
                    <div>
                        <p class="text-lg font-bold leading-tight">Perseus Safety and</p>
                        <p class="text-lg font-bold leading-tight">Security Agency</p>
                        <p class="mt-1 text-xs tracking-[0.18em] uppercase text-slate-400">Guard Management System</p>
                    </div>
                </div>
            </div>

            <div class="px-5 pt-5 pb-3">
                <p class="px-3 text-[11px] font-semibold uppercase tracking-[0.22em] text-slate-500">
                    Navigation
                </p>
            </div>

            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all duration-200
                   {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-xl shadow-cyan-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center {{ request()->routeIs('dashboard') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Dashboard</p>
                        <p class="text-xs {{ request()->routeIs('dashboard') ? 'text-cyan-100' : 'text-slate-500 group-hover:text-slate-300' }}">Live overview</p>
                    </div>
                </a>

                <a href="{{ route('guards.index') }}"
                   class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all duration-200
                   {{ request()->routeIs('guards.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-xl shadow-cyan-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center {{ request()->routeIs('guards.*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs('guards.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20h6M12 16v4M8 8a4 4 0 118 0 4 4 0 01-8 0Zm-1 8a5 5 0 0110 0" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Guards</p>
                        <p class="text-xs {{ request()->routeIs('guards.*') ? 'text-cyan-100' : 'text-slate-500 group-hover:text-slate-300' }}">Personnel records</p>
                    </div>
                </a>

                <a href="{{ route('companies.index') }}"
                   class="group flex items-center gap-3 rounded-2xl px-4 py-3.5 transition-all duration-200
                   {{ request()->routeIs('companies.*') ? 'bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-xl shadow-cyan-900/20' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <div class="h-10 w-10 rounded-xl flex items-center justify-center {{ request()->routeIs('companies.*') ? 'bg-white/15' : 'bg-white/5 group-hover:bg-white/10' }}">
                        <svg class="h-5 w-5 {{ request()->routeIs('companies.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h16M6 21V7l6-4 6 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold">Companies</p>
                        <p class="text-xs {{ request()->routeIs('companies.*') ? 'text-cyan-100' : 'text-slate-500 group-hover:text-slate-300' }}">Deployment clients</p>
                    </div>
                </a>
            </nav>

            <div class="p-4">
                <div class="rounded-3xl border border-white/10 bg-white/5 p-4 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Signed In</p>
                    <p class="mt-3 text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>

                    <form action="{{ route('logout') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-2xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- MAIN --}}
        <div class="flex-1 h-screen overflow-y-auto">
            <div class="min-h-full p-6 md:p-8">
                <div class="mx-auto max-w-[1500px]">

                    @if(session('success'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
                            <strong>Please fix the following errors:</strong>
                            <ul class="mt-2 ml-6 list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="rounded-[34px] border border-white/80 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.08)] p-6 md:p-8">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(request()->routeIs('dashboard'))
        <script>
            function updateLiveDateTime() {
                const now = new Date();
                const formatted = now.toLocaleString('en-US', {
                    timeZone: 'Asia/Manila',
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: '2-digit',
                    hour: 'numeric',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true,
                });

                const el = document.getElementById('liveDateTime');
                if (el) {
                    el.textContent = formatted;
                }
            }

            updateLiveDateTime();
            setInterval(updateLiveDateTime, 1000);
        </script>
    @endif
</body>
</html>
