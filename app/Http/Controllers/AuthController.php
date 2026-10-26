<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Fish;
use App\Models\FishStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole(Auth::user());
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our ocean records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:student,teacher',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'onboarded' => true,
            'avatar' => $validated['role'] === 'teacher' ? '👩‍🏫' : '🐠',
        ]);

        if ($user->isStudent()) {
            StudentProfile::create([
                'user_id' => $user->id,
                'display_name' => $user->name,
                'total_points' => 0,
                'current_streak' => 1,
                'last_activity_date' => now()->toDateString(),
            ]);

            $stage = FishStage::where('name', 'tiny_fry')->first() ?? FishStage::first();
            Fish::create([
                'user_id' => $user->id,
                'name' => 'Little ' . explode(' ', $user->name)[0],
                'species' => 'clownfish',
                'color' => '#FF7A00',
                'size' => 0.75,
                'stage_id' => $stage->id,
                'total_growth_points' => 0,
            ]);
        }

        Auth::login($user);
        return $this->redirectBasedOnRole($user);
    }

    public function switchDemo(string $persona)
    {
        $emailMap = [
            'teacher' => 'teacher@aquaclass.com',
            'student' => 'leo@aquaclass.com',
            'leo' => 'leo@aquaclass.com',
            'maya' => 'maya@aquaclass.com',
            'kai' => 'kai@aquaclass.com',
            'zara' => 'zara@aquaclass.com',
            'admin' => 'admin@aquaclass.com',
        ];

        $targetEmail = $emailMap[$persona] ?? 'leo@aquaclass.com';
        $user = User::where('email', $targetEmail)->first();

        if ($user) {
            Auth::login($user);
            return $this->redirectBasedOnRole($user)->with('success', "Logged in as {$user->name} ({$user->role})!");
        }

        return redirect('/')->with('error', 'Demo user not found.');
    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Throwable $e) {
            // Friendly fallback if Google API keys are dummy in .env
            return redirect()->route('login')->with('info', 'Google OAuth is configured with demo placeholders. Use Quick Demo Switcher or regular login.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'Aqua Explorer',
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar() ?? '🐠',
                    'role' => 'student',
                    'onboarded' => true,
                ]);

                StudentProfile::create([
                    'user_id' => $user->id,
                    'display_name' => $user->name,
                    'total_points' => 0,
                    'current_streak' => 1,
                    'last_activity_date' => now()->toDateString(),
                ]);

                $stage = FishStage::where('name', 'tiny_fry')->first() ?? FishStage::first();
                Fish::create([
                    'user_id' => $user->id,
                    'name' => 'Little ' . explode(' ', $user->name)[0],
                    'species' => 'clownfish',
                    'color' => '#FF7A00',
                    'size' => 0.75,
                    'stage_id' => $stage ? $stage->id : 1,
                    'total_growth_points' => 0,
                ]);
            } else {
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            Auth::login($user);
            return $this->redirectBasedOnRole($user)->with('success', "Welcome back, {$user->name}!");
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Google login could not be completed: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectBasedOnRole(User $user)
    {
        if ($user->isTeacher()) {
            return redirect()->route('teacher.dashboard');
        } elseif ($user->isAdmin()) {
            return redirect()->route('teacher.dashboard'); // or admin view
        }
        return redirect()->route('student.dashboard');
    }
}
