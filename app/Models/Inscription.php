<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'evenement_id',
        'date_inscription',
        'statut',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    // L'étudiant inscrit
    public function etudiant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // L'événement concerné
    public function evenement()
    {
        return $this->belongsTo(Evenement::class);
    }

    // Helpers de statut
    public function estConfirmee(): bool
    {
        return $this->statut === 'confirmee';
    }

    public function estEnAttente(): bool
    {
        return $this->statut === 'liste_attente';
    }

    public function estAnnulee(): bool
    {
        return $this->statut === 'annulee';
    }
}