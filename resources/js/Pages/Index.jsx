// resources/js/Pages/Inscriptions/Index.jsx

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';

export default function Index({ inscriptions }) {

    const badgeStatut = (statut) => {
        switch (statut) {
            case 'confirmee':
                return 'bg-green-100 text-green-700';
            case 'liste_attente':
                return 'bg-yellow-100 text-yellow-700';
            case 'annulee':
                return 'bg-red-100 text-red-700';
        }
    };

    const labelStatut = (statut) => {
        switch (statut) {
            case 'confirmee':    return '✅ Confirmée';
            case 'liste_attente': return '⏳ Liste d\'attente';
            case 'annulee':     return '❌ Annulée';
        }
    };

    return (
        <AuthenticatedLayout>
            <div className="max-w-4xl mx-auto p-6">
                <h1 className="text-2xl font-bold text-gray-800 mb-6">
                    Mes Inscriptions
                </h1>

                {inscriptions.length === 0 ? (
                    <div className="text-center text-gray-400 py-12">
                        Aucune inscription pour le moment.
                    </div>
                ) : (
                    <div className="space-y-4">
                        {inscriptions.map((inscription) => (
                            <div
                                key={inscription.id}
                                className="bg-white rounded-xl shadow p-5 
                                           flex justify-between items-center"
                            >
                                <div>
                                    <h2 className="font-semibold text-gray-800">
                                        {inscription.evenement.nom}
                                    </h2>
                                    <p className="text-sm text-gray-400 mt-1">
                                        Inscrit le : {inscription.date_inscription}
                                    </p>
                                </div>

                                <span className={`px-3 py-1 rounded-full text-sm 
                                                  font-medium ${badgeStatut(inscription.statut)}`}>
                                    {labelStatut(inscription.statut)}
                                </span>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </AuthenticatedLayout>
    );
}