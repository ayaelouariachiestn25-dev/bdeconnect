<?php

namespace App\Services;

use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\InscriptionHistorique;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InscriptionService
{
    /**
     * RG1 + RG2 : Inscription avec liste d'attente automatique
     */
    public function inscrire(User $user, Evenement $evenement): Inscription
    {
        // RG2 : Vérifier unicité (pas déjà inscrit, même en attente)
        $existing = Inscription::where('user_id', $user->id)
            ->where('evenement_id', $evenement->id)
            ->whereIn('statut', ['confirmee', 'liste_attente'])
            ->first();
        if ($existing) {
            throw new \Exception("Vous êtes déjà inscrit à cet événement.");
        }

        // Compter les places déjà confirmées
        $nbConfirmees = Inscription::where('evenement_id', $evenement->id)
            ->where('statut', 'confirmee')
            ->count();

        // RG1 : Déterminer le statut
        $statut = ($nbConfirmees < $evenement->capacite_max) ? 'confirmee' : 'liste_attente';

        // Créer l'inscription
        $inscription = Inscription::create([
            'user_id' => $user->id,
            'evenement_id' => $evenement->id,
            'statut' => $statut,
            'date_inscription' => now(),
        ]);

        // Historique : création
        $this->historiser($inscription, null, $statut);

        return $inscription;
    }

    /**
     * Annuler une inscription (et promouvoir la liste d'attente si c'était une confirmation)
     */
    public function annuler(Inscription $inscription): void
    {
        $ancienStatut = $inscription->statut;
        if ($ancienStatut === 'annulee') {
            throw new \Exception("Cette inscription est déjà annulée.");
        }

        $inscription->statut = 'annulee';
        $inscription->save();

        $this->historiser($inscription, $ancienStatut, 'annulee');

        // RG3 : Si l'inscription annulée était confirmée, promouvoir la liste d'attente
        if ($ancienStatut === 'confirmee') {
            $this->promouvoirListeAttente($inscription->evenement);
        }
    }

    /**
     * RG3 : Promouvoir le premier en liste d'attente vers confirmé
     */
    public function promouvoirListeAttente(Evenement $evenement): ?Inscription
    {
        $prochain = Inscription::where('evenement_id', $evenement->id)
            ->where('statut', 'liste_attente')
            ->orderBy('date_inscription')
            ->first();

        if (!$prochain) {
            return null;
        }

        $ancienStatut = $prochain->statut;
        $prochain->statut = 'confirmee';
        $prochain->save();

        $this->historiser($prochain, $ancienStatut, 'confirmee');

        return $prochain;
    }

    /**
     * Enregistrer chaque changement dans l'historique
     */
    protected function historiser(Inscription $inscription, ?string $ancien, string $nouveau): void
    {
        InscriptionHistorique::create([
            'inscription_id' => $inscription->id,
            'ancien_statut'  => $ancien ?? $nouveau, // si création, ancien = nouveau
            'nouveau_statut' => $nouveau,
        ]);
    }
}