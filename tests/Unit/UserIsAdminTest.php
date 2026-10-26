<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserIsAdminTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config()->set('auth.admin_emails', ['admin@cmsippi.com']);
    }

    public function test_user_with_admin_role_is_admin(): void
    {
        $user = new User(['email' => 'admin@cmsippi.com', 'role' => 'admin']);

        $this->assertTrue($user->isAdmin());
    }

    public function test_configured_admin_email_is_admin_without_admin_role(): void
    {
        $user = new User(['email' => 'admin@cmsippi.com', 'role' => 'user']);

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_with_non_admin_email_or_role_is_not_admin(): void
    {
        $user = new User(['email' => 'user@example.com', 'role' => 'user']);

        $this->assertFalse($user->isAdmin());
    }
}
