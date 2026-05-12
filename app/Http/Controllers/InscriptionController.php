<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Inscription;
use App\Services\InscriptionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InscriptionController extends Controller
{
    public function __construct(private InscriptionService $service) {}

    public function store(Request $request, Evenement $evenement)
    {
        try {
            $inscription = $this->service->inscrire(
                auth()->user(),
                $evenement
            );

            return back()->with('success', 
                $inscription->statut === 'confirmée'
                    ? 'Inscription confirmée !'
                    : 'Vous êtes en liste d\'attente.'
            );

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Inscription $inscription)
    {
        // Vérifier que c'est bien l'étudiant connecté
        if ($inscription->user_id !== auth()->id()) {
            abort(403);
        }

        $this->service->annuler($inscription);

        return back()->with('success', 'Inscription annulée.');
    }
}
