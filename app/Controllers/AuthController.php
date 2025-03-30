<?php

namespace App\Controllers;

use Framework\Request;
use Framework\Response;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegisterForm(Request $request)
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    /**
     * Process registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required'
        ], [
            'name.required' => 'Please enter your name',
            'name.min' => 'Your name must be at least 3 characters',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Please enter a password',
            'password.min' => 'Your password must be at least 6 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'password_confirmation.required' => 'Please confirm your password'
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => password_hash($validated['password'], PASSWORD_DEFAULT),
        ]);

        // Use object property - id_user property mapped to id via __get
        session('user_id', $user->id);
        session('user_name', $user->name);

        toast_success('Your account has been created successfully!');

        // Redirect to dashboard
        return redirect('/dashboard');
    }

    /**
     * Show the login form
     */
    public function showLoginForm(Request $request)
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Please enter your password'
        ]);

        // Find user by email
        $user = User::findByEmail($validated['email']);

        // Check if user exists and password is correct
        if (!$user || !$user->verifyPassword($validated['password'])) {
            // Store error in session
            session('_errors', ['email' => 'Invalid email or password']);

            // Store old input in session except for the password
            session('_old_input', $request->except(['password']));

            toast_error('Login failed. Please check your credentials.');

            return $this->redirect('/login');
        }

        // If validation passes, store user ID in session - id_user property mapped to id via __get
        session('user_id', $user->id);
        session('user_name', $user->name);

        toast_success('Welcome back, ' . $user->name . '!');

        // Redirect to dashboard
        return $this->redirect('/dashboard');
    }

    /**
     * Process logout
     */
    public function logout(Request $request)
    {
        // Save the flash message before destroying the session
        $flashMessage = 'You have been logged out successfully.';

        // Start new session for flash message
        session_start();
        // Unset all session variables
        session_unset();
        // Regenerate the session ID
        session_regenerate_id(true);
        // Destroy the session completely
        session_destroy();

        // Start a fresh session for the flash message
        session_start();
        // Add a flash message
        toast_info($flashMessage);

        // Redirect to home with special parameter to avoid caching
        return $this->redirect('/?logout=' . time());
    }
}
