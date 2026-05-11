<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'description',
        'lieu',
        'date_debut',
        'date_fin',
        'capacite_max',
        'prix',
        'image',
    ];

    protected $casts = [
        'date_debut'   => 'datetime',
        'date_fin'     => 'datetime',
        'capacite_max' => 'integer',
        'prix'         => 'decimal:2',
    ];

    // Admin qui a créé l'événement
    public function createur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Toutes les inscriptions
    public function inscriptions()
    {
        return $this->hasMany(Inscription::class);
    }

    // Inscriptions confirmées uniquement
    public function inscriptionsConfirmees()
    {
        return $this->hasMany(Inscription::class)
                    ->where('statut', 'confirmee');
    }

    // Liste d'attente (ordre chronologique pour RG3)
    public function listeAttente()
    {
        return $this->hasMany(Inscription::class)
                    ->where('statut', 'liste_attente')
                    ->orderBy('date_inscription', 'asc');
    }

    // Nombre de places confirmées
    public function getNbConfirmeesAttribute(): int
    {
        return $this->inscriptionsConfirmees()->count();
    }

    // Nombre de places restantes
    public function getPlacesRestantesAttribute(): int
    {
        return max(0, $this->capacite_max - $this->nb_confirmees);
    }

    // Événement à venir ? (RG5)
    public function getEstAVenirAttribute(): bool
    {
        return $this->date_fin->isFuture();
    }

    // Événement passé ? (RG4)
    public function getEstPasseAttribute(): bool
    {
        return $this->date_fin->isPast();
    }
}