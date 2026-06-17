<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use App\Traits\LogsActivity;

class User extends Authenticatable
{
    // use Notifiable, LogsActivity;

    protected $fillable = ['name', 'email', 'password', 'role', 'actif'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'password' => 'hashed',
        'actif' => 'boolean',
    ];

    protected $attributes = [
        'actif' => true,
    ];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isAchat(): bool { return $this->role === 'achat'; }
    public function isFinance(): bool { return $this->role === 'finance'; }
    public function isDirection(): bool { return $this->role === 'direction'; }
}