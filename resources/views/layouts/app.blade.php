<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Guard Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 h-screen overflow-hidden text-slate-800">
    <div class="h-screen flex overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-[280px] h-screen bg-slate-900 text-white flex flex-col shadow-2xl shrink-0">
            <div class="px-6 py-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-blue-600 flex items-center justify-center text-lg font-bold shadow-md">
                        SG
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-tight">Guard Management</h1>
                        <p class="text-xs text-slate-400">Security Guard System</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" />
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('guards.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs('guards.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('guards.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5V4H2v16h5" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20h6M12 16v4M8 8a4 4 0 118 0 4 4 0 01-8 0Zm-1 8a5 5 0 0110 0" />
                    </svg>
                    <span class="font-medium">Guards</span>
                </a>

                <a href="{{ route('companies.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
                   {{ request()->routeIs('companies.*') ? 'bg-blue-600 text-white shadow-md' : 'text-slate-200 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('companies.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 21h16M6 21V7l6-4 6 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01" />
                    </svg>
                    <span class="font-medium">Companies</span>
                </a>
            </nav>

            <div class="px-4 pb-4">
                <div class="rounded-2xl bg-slate-800/80 border border-slate-700 p-4">
                    <p class="text-sm font-semibold text-white">System Panel</p>
                    <p class="mt-1 text-xs text-slate-400">
                        Manage guards, license validity, and company deployment records.
                    </p>
                </div>
            </div>
        </aside>

        {{-- RIGHT SIDE ONLY SCROLLS --}}
        <div class="flex-1 h-screen overflow-y-auto">
            <main class="p-6 md:p-8">
                <div class="max-w-7xl mx-auto">
                    @if(session('success'))
                        <div class="mb-5 rounded-2xl bg-green-50 border border-green-200 text-green-800 px-5 py-4 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 text-red-800 px-5 py-4 shadow-sm">
                            <strong>Please fix the following errors:</strong>
                            <ul class="list-disc ml-6 mt-2 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm min-h-[calc(100vh-4rem)] p-6 md:p-8">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>