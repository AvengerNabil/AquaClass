<x-layouts.app title="Register — AquaClass">
    <div class="max-w-md mx-auto px-4 py-12">
        <div class="ocean-glass rounded-3xl p-8 sm:p-10 shadow-2xl border-2 border-sky-300">
            
            <div class="text-center mb-6">
                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-gradient-to-tr from-cyan-500 to-blue-600 flex items-center justify-center text-2xl shadow-md shadow-cyan-500/25">
                    🌊
                </div>
                <h1 class="text-2xl font-black text-slate-900">
                    Create Your Account
                </h1>
                <p class="text-xs text-slate-500 font-medium mt-1">
                    Start growing your virtual fish while mastering school subjects!
                </p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Your Name
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Leo Starfish" class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Role
                    </label>
                    <select name="role" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-sm text-slate-800">
                        <option value="student">Student (Grade 1, 2, or 3) 🐠</option>
                        <option value="teacher">Teacher (Classroom Host) 👩‍🏫</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Password
                    </label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-1.5">
                        Confirm Password
                    </label>
                    <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-2xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-medium text-sm text-slate-800">
                </div>

                <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-cyan-500 via-sky-600 to-blue-600 text-white font-extrabold text-sm shadow-md shadow-cyan-500/25 hover:scale-[1.02] active:scale-[0.98] transition">
                    Create Account & Hatch Fish 🐣
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-slate-200/60 text-center text-xs text-slate-500 font-medium">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-cyan-700 font-extrabold hover:underline">
                    Sign In
                </a>
            </div>

        </div>
    </div>
</x-layouts.app>
