<x-layouts.app title="Sign In — AquaClass">
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="ocean-glass rounded-3xl p-8 sm:p-10 shadow-2xl border-2 border-sky-300">
            
            <div class="text-center mb-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-2xl shadow-md shadow-cyan-500/25">
                    🐠
                </div>
                <h1 class="text-2xl font-black text-slate-900">
                    Welcome Back!
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Sign in to check on your virtual fish and classroom adventures.
                </p>
            </div>

            <!-- Quick Demo 1-Click Login Card -->
            <div class="mb-6 p-4 rounded-2xl bg-cyan-50 border border-cyan-200 text-left">
                <span class="block text-[11px] font-black text-cyan-900 uppercase tracking-wide mb-2">⚡ 1-Click Demo Login</span>
                <div class="grid grid-cols-2 gap-2">
                    <a href="{{ route('demo.switch', 'teacher') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-cyan-100/70 border border-cyan-200 text-xs font-bold text-slate-800 text-center transition">
                        👩‍🏫 Ms. Clara (Teacher)
                    </a>
                    <a href="{{ route('demo.switch', 'leo') }}" class="px-3 py-2 rounded-xl bg-white hover:bg-cyan-100/70 border border-cyan-200 text-xs font-bold text-slate-800 text-center transition">
                        ⭐ Leo (Student)
                    </a>
                </div>
            </div>

            <form action="{{ route('login') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email', 'teacher@aquaclass.com') }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide">
                            Password
                        </label>
                    </div>
                    <input type="password" name="password" value="password123" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <div class="flex items-center justify-between text-xs font-bold text-slate-600">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-cyan-600 focus:ring-cyan-500">
                        <span>Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 via-sky-600 to-blue-600 text-white font-extrabold text-sm shadow-md shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition">
                    Sign In to AquaClass ✨
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200/60 text-center text-xs text-slate-500 font-medium">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-cyan-700 font-extrabold hover:underline">
                    Register Free
                </a>
            </div>

        </div>
    </div>
</x-layouts.app>
