<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminViewComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $isAdmin = false;
        
        if (Auth::check()) {
            $user = Auth::user();
            $adminEmails = array_map('trim', explode(',', env('ADMIN_EMAILS', 'admin@company.com')));
            
            if (isset($user->email)) {
                $userEmail = strtolower($user->email);
                $normalizedAdminEmails = array_map('strtolower', $adminEmails);
                $isAdmin = in_array($userEmail, $normalizedAdminEmails, true);
            }
        }
        
        $view->with('isAdmin', $isAdmin);
    }
}