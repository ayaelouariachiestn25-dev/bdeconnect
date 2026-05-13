<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InscriptionHistorique extends Model
{
    protected $fillable = ['inscription_id', 'ancien_statut', 'nouveau_statut'];

    public function inscription()
    {
        return $this->belongsTo(Inscription::class);
    }
}