// resources/js/Pages/Evenements/Show.jsx

import { router } from '@inertiajs/react';

export default function Show({ evenement, inscription }) {

    const sInscrire = () => {
        router.post(`/evenements/${evenement.id}/inscrire`);
    };

    const annuler = () => {
        router.delete(`/inscriptions/${inscription.id}`);
    };

    return (
        <div className="p-6">
            <h1 className="text-2xl font-bold">{evenement.nom}</h1>
            <p>Capacité : {evenement.capacite_max} places</p>

            {!inscription && (
                <button
                    onClick={sInscrire}
                    className="mt-4 bg-blue-600 text-white px-4 py-2 rounded"
                >
                    S'inscrire
                </button>
            )}

            {inscription && inscription.statut === 'confirmée' && (
                <div>
                    <p className="text-green-600">✅ Inscription confirmée</p>
                    <button onClick={annuler} className="text-red-500 underline">
                        Annuler
                    </button>
                </div>
            )}

            {inscription && inscription.statut === 'liste_attente' && (
                <div>
                    <p className="text-yellow-600">⏳ En liste d'attente</p>
                    <button onClick={annuler} className="text-red-500 underline">
                        Annuler
                    </button>
                </div>
            )}
        </div>
    );
}

