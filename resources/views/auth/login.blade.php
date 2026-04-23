<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Perseus Safety and Security Agency</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-slate-800">
    <div class="min-h-screen grid lg:grid-cols-2">
        <div class="hidden lg:flex items-center justify-center p-12">
            <div class="max-w-xl text-white">
                <div class="flex items-center gap-4 mb-8">
                    <div class="h-20 w-20 rounded-3xl bg-white/10 border border-white/10 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold leading-tight">Perseus Safety and</h1>
                        <h1 class="text-3xl font-bold leading-tight">Security Agency</h1>
                    </div>
                </div>

                <p class="text-slate-300 text-lg leading-8">
                    Securely manage guard records, monitor license expiration alerts, and oversee deployment operations from one control center.
                </p>
            </div>
        </div>

        <div class="flex items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-md rounded-[32px] bg-white shadow-2xl border border-white/60 p-8 md:p-10">
                @if(session('success'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="text-center mb-8">
                    <div class="mx-auto h-20 w-20 rounded-3xl bg-slate-100 flex items-center justify-center overflow-hidden border border-slate-200">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">
                    </div>
                    <h2 class="mt-5 text-2xl font-bold text-slate-900">Welcome Back</h2>
                    <p class="mt-2 text-sm text-slate-500">Log in to access the guard management system.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                               placeholder="Enter your email"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <input type="password"
                               name="password"
                               class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-100 outline-none"
                               placeholder="Enter your password"
                               required>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300">
                        <label for="remember" class="text-sm text-slate-600">Remember me</label>
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/20 hover:opacity-95 transition">
                        Log In
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-slate-500">
                    No account yet?
                    <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                        Create one here
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
