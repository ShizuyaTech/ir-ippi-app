<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const SAFETY_REPORT_ROLES = [
        'user' => 'User',
        'admin' => 'Administrator',
        'safety_pic' => 'PIC Safety & Healthy',
        'ir_reviewer' => 'Reviewer Industrial Relationship',
        'she' => 'Tim Safety, Healthy & Environment',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        'password' => 'hashed',
    ];

    /**
     * Return true if the user is an administrator.
     *
     * Admin access is granted by the admin role or the configured admin email list.
     */
    public function isAdmin(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        $adminEmails = array_map('strtolower', config('auth.admin_emails', []));

        return $this->email !== null && in_array(strtolower($this->email), $adminEmails, true);
    }

    public function hasSafetyReportRole(string ...$roles): bool
    {
        return $this->isAdmin() || in_array($this->role, $roles, true);
    }
}
