<?php

namespace Features\Home;

class HomeController
{
    /**
     * Show the home page
     */
    public function index()
    {
        // Get authenticated user
        $user = user();

        return inertia('home/pages/Dashboard', [
            'user' => $user,
            'stats' => [
                'welcome_message' => 'Welcome to your home page, ' . ($user['name'] ?? 'User') . '!',
            ]
        ]);
    }
}
