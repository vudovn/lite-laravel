<?php

namespace App\Controllers;
use Framework\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     */
    public function index(Request $request)
    {
        $user = auth();
        return view('dashboard.index', [
            'title' => 'Dashboard',
            'user' => $user
        ]);
    }

    /**
     * Show the user profile
     */
    public function profile(Request $request)
    {
        // Get the authenticated user using the auth() helper
        $user = auth();

        if (!$user) {
            toast_error('Your session has expired. Please login again.');
            return $this->redirect('/login');
        }

        return view('dashboard.profile', [
            'title' => 'My Profile',
            'user' => $user
        ]);
    }
}
