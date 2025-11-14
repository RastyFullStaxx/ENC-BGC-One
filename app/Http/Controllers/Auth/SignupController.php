<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class SignupController extends Controller
{
    /**
     * Show staff signup form
     */
    public function showStaffForm()
    {
        return view('auth.signup.staff-signup');
    }

    /**
     * Handle staff signup
     */
    public function registerStaff(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._-]+@ministry\.gov$/'
            ],
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'department_id' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'email.regex' => 'Email must be in the format: your.name@ministry.gov',
            'department_id.required' => 'Please select a department.'
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
                ->withInput();
        }

        try {
            // Create staff user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department' => $request->department_id, // Store department name directly
                'role' => 'staff',
                'password' => Hash::make($request->password),
            ]);

            // Check if it's an AJAX request
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account created successfully! Please log in.',
                    'loginUrl' => route('login'),
                    'landingUrl' => route('landing')
                ], 200);
            }

            // Redirect to login with success message
            return redirect()->route('login')->with('success', 'Account created successfully! Please log in.');
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database errors (like unique constraint violations)
            $errorMessage = 'An error occurred while creating your account.';
            
            // Check for specific unique constraint violations
            if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                $errorMessage = 'Try another email address.';
                $field = 'email';
            } elseif (strpos($e->getMessage(), 'users_phone_unique') !== false) {
                $errorMessage = 'Try another phone number.';
                $field = 'phone';
            }
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => isset($field) ? [$field => [$errorMessage]] : [],
                    'message' => $errorMessage
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(isset($field) ? [$field => $errorMessage] : ['error' => $errorMessage])
                ->withInput();
        }
    }
}
