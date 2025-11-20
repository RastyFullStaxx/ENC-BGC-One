<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        // Prevent caching of login page
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        return view('auth.login.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._-]+@ministry\.gov$/'
            ],
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.regex' => 'Email must be in the format: your.name@ministry.gov',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        if ($validator->fails()) {
            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // Attempt to authenticate
        $credentials = $request->only('email', 'password');
        $credentials['status'] = 'active';
        
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            $user->forceFill([
                'last_login_at' => now(),
            ])->save();
            
            // Determine redirect URL based on role
            if ($user->role === 'admin') {
                $redirectUrl = route('admin.dashboard');
            } else {
                $redirectUrl = route('user.dashboard');
            }
            
            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                // Redirect to loading page with query parameter
                $loadingUrl = route('login.loading') . '?redirect=' . urlencode($redirectUrl);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Login successful!',
                    'redirectUrl' => $loadingUrl,
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ], 200);
            }
            
            return redirect()->intended($redirectUrl);
        }

        // Authentication failed
        $matchedUser = User::where('email', $request->input('email'))->first();
        if ($matchedUser && $matchedUser->status !== 'active') {
            $statusMessage = $matchedUser->status === 'inactive'
                ? 'This account has been deactivated. Please contact an admin to restore access.'
                : 'This account is pending activation.';

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => [
                        'email' => [$statusMessage]
                    ],
                    'message' => 'Authentication failed'
                ], 423);
            }

            return redirect()->back()
                ->withErrors(['email' => $statusMessage])
                ->withInput($request->only('email'));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'errors' => [
                    'password' => ['The provided credentials do not match our records.']
                ],
                'message' => 'Authentication failed'
            ], 401);
        }

        return redirect()->back()
            ->withErrors(['password' => 'The provided credentials do not match our records.'])
            ->withInput($request->only('email'));
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Support GET fallback for users with stale CSRF tokens
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('landing');
    }
}
