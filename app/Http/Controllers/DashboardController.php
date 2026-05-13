<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Inscription;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_evenements'   => Evenement::count(),
            'total_inscriptions' => Inscription::where('statut', 'confirmee')->count(),
            'total_etudiants'    => User::where('role', 'etudiant')->count(),
            'total_attente'      => Inscription::where('statut', 'liste_attente')->count(),
        ];

        $graphique = Evenement::withCount([
            'inscriptions as nb_confirmees' => fn($q) => $q->where('statut', 'confirmee'),
            'inscriptions as nb_attente'    => fn($q) => $q->where('statut', 'liste_attente'),
        ])
        ->orderBy('date_debut', 'desc')
        ->take(5)
        ->get()
        ->map(fn($e) => [
            'titre'      => $e->titre,
            'confirmees' => $e->nb_confirmees,
            'attente'    => $e->nb_attente,
            'capacite'   => $e->capacite_max,
        ]);

        return Inertia::render('Admin/Dashboard', [
            'stats'     => $stats,
            'graphique' => $graphique,
        ]);
    }

    public function exportCsv()
    {
        $inscriptions = Inscription::with(['etudiant', 'evenement'])
            ->whereIn('statut', ['confirmee', 'liste_attente'])
            ->get();

        $csv = "Étudiant,Email,Événement,Statut,Date inscription\n";

        foreach ($inscriptions as $i) {
            $csv .= "{$i->etudiant->name},{$i->etudiant->email},{$i->evenement->titre},{$i->statut},{$i->date_inscription}\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="inscriptions.csv"');
    }
}