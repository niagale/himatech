<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id', 'action', 'table_name', 'record_id',
        'old_values', 'new_values', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute()
    {
        return match($this->action) {
            'create' => '➕ Création',
            'update' => '✏️ Modification',
            'delete' => '🗑️ Suppression',
            'login'  => '🔐 Connexion',
            'logout' => '🚪 Déconnexion',
            default  => $this->action,
        };
    }
}