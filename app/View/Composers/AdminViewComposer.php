<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminViewComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $isAdmin = Auth::user()?->isAdmin() ?? false;

        $view->with('isAdmin', $isAdmin);
    }
}
