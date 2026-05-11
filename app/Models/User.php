<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Événements créés par cet admin
    public function evenements()
    {
        return $this->hasMany(Evenement::class);
    }

    // Inscriptions de cet étudiant
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    // Vérifier si admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Vérifier si étudiant
    public function isEtudiant(): bool
    {
        return $this->role === 'etudiant';
    }
}