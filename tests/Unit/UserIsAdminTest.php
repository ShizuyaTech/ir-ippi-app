<?php

namespace Tests\Unit;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserIsAdminTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Ensure default admin email for tests
        putenv('ADMIN_EMAILS=admin@company.com');
    }

    public function test_user_with_admin_email_is_admin()
    {
        $user = new User();
        $user->email = 'admin@company.com';

        $this->assertTrue($user->isAdmin());
    }

    public function test_user_with_non_admin_email_is_not_admin()
    {
        $user = new User();
        $user->email = 'user@example.com';

        $this->assertFalse($user->isAdmin());
    }
}
