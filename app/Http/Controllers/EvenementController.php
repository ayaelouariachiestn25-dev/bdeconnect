<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Inscription;
use App\Services\InscriptionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvenementController extends Controller
{
    protected $inscriptionService;

    public function __construct(InscriptionService $inscriptionService)
    {
        $this->inscriptionService = $inscriptionService;
    }

    // ─── Côté Étudiant ────────────────────────────────────────

    // Liste des événements à venir (RG5)
    public function index()
    {
        $evenements = Evenement::where('date_fin', '>', now())
            ->withCount(['inscriptions as nb_confirmees' => function ($q) {
                $q->where('statut', 'confirmee');
            }])
            ->orderBy('date_debut')
            ->get()
            ->map(function ($e) {
                $e->places_restantes = max(0, $e->capacite_max - $e->nb_confirmees);
                $e->mon_inscription = $e->inscriptions()
                    ->where('user_id', auth()->id())
                    ->first();
                return $e;
            });

        return Inertia::render('Evenements/Index', [
            'evenements' => $evenements,
        ]);
    }

    // Détail d'un événement
    public function show(Evenement $evenement)
    {
        $evenement->load('inscriptions.etudiant');
        $evenement->nb_confirmees = $evenement->inscriptionsConfirmees()->count();
        $evenement->places_restantes = max(0, $evenement->capacite_max - $evenement->nb_confirmees);
        $evenement->mon_inscription = $evenement->inscriptions()
            ->where('user_id', auth()->id())
            ->first();

        return Inertia::render('Evenements/Show', [
            'evenement' => $evenement,
        ]);
    }

    // Inscription à un événement (RG1, RG2)
    public function inscrire(Evenement $evenement)
    {
        try {
            $inscription = $this->inscriptionService->inscrire(auth()->user(), $evenement);
            $message = $inscription->statut === 'confirmee'
                ? 'Inscription confirmée !'
                : 'Vous êtes en liste d\'attente.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Annulation + promotion automatique (RG3)
    public function annuler(Inscription $inscription)
    {
        if ($inscription->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $this->inscriptionService->annuler($inscription);
            return back()->with('success', 'Inscription annulée.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ─── Côté Admin ───────────────────────────────────────────

    // Liste tous les événements (admin)
    public function adminIndex()
    {
        $evenements = Evenement::withCount([
            'inscriptions as nb_confirmees' => fn($q) => $q->where('statut', 'confirmee'),
            'inscriptions as nb_attente'    => fn($q) => $q->where('statut', 'liste_attente'),
        ])
        ->orderBy('date_debut', 'desc')
        ->get();

        return Inertia::render('Admin/Evenements/Index', [
            'evenements' => $evenements,
        ]);
    }

    // Formulaire création
    public function create()
    {
        return Inertia::render('Admin/Evenements/Create');
    }

    // Enregistrer un événement
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'lieu'         => 'nullable|string|max:255',
            'date_debut'   => 'required|date',
            'date_fin'     => 'required|date|after:date_debut',
            'capacite_max' => 'required|integer|min:1',
            'prix'         => 'nullable|numeric|min:0',
        ]);

        $validated['user_id'] = auth()->id();

        Evenement::create($validated);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement créé avec succès !');
    }

    // Formulaire modification
    public function edit(Evenement $evenement)
    {
        // RG4 : bloquer si événement passé
        if ($evenement->date_fin < now()) {
            return redirect()->route('admin.evenements.index')
                ->with('error', 'Impossible de modifier un événement terminé.');
        }

        return Inertia::render('Admin/Evenements/Edit', [
            'evenement' => $evenement,
        ]);
    }

    // Mettre à jour
    public function update(Request $request, Evenement $evenement)
    {
        // RG4 : bloquer si événement passé
        if ($evenement->date_fin < now()) {
            abort(403, 'Modification impossible : événement terminé.');
        }

        $validated = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'lieu'         => 'nullable|string|max:255',
            'date_debut'   => 'required|date',
            'date_fin'     => 'required|date|after:date_debut',
            'capacite_max' => 'required|integer|min:1',
            'prix'         => 'nullable|numeric|min:0',
        ]);

        $evenement->update($validated);

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement modifié avec succès !');
    }

    // Supprimer
    public function destroy(Evenement $evenement)
    {
        $evenement->delete();

        return redirect()->route('admin.evenements.index')
            ->with('success', 'Événement supprimé.');
    }
}