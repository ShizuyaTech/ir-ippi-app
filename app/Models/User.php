<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // optional role fields used by isAdmin()
        'role',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Return true if the user is an administrator.
     *
     * This method supports two common patterns:
     *  - a boolean `is_admin` column; or
     *  - a string `role` column that equals 'admin'.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        // Admin defined by email(s). Allow comma-separated list in ADMIN_EMAILS env var.
        // Default admin email: admin@company.com
        $adminEmails = array_map('trim', explode(',', env('ADMIN_EMAILS', 'admin@company.com')));

        if (!isset($this->email) || empty($this->email)) {
            return false;
        }

        $userEmail = strtolower($this->email);
        $normalized = array_map('strtolower', $adminEmails);

        return in_array($userEmail, $normalized, true);
    }
}
