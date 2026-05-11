import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';

export default function Index({ evenements, flash }) {
    const inscrire = (id) => {
        router.post(`/evenements/${id}/inscrire`);
    };

    return (
        <AuthenticatedLayout>
            <Head title="Événements" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-2xl font-bold mb-6 text-gray-800">
                        Événements à venir
                    </h1>

                    {flash?.success && (
                        <div className="mb-4 p-4 bg-green-100 text-green-700 rounded">
                            {flash.success}
                        </div>
                    )}
                    {flash?.error && (
                        <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
                            {flash.error}
                        </div>
                    )}

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {evenements.map((e) => (
                            <div key={e.id} className="bg-white rounded-lg shadow p-6">
                                <h2 className="text-xl font-semibold text-gray-800 mb-2">
                                    {e.titre}
                                </h2>
                                <p className="text-gray-500 text-sm mb-1">
                                    📍 {e.lieu || 'Lieu non précisé'}
                                </p>
                                <p className="text-gray-500 text-sm mb-1">
                                    📅 {new Date(e.date_debut).toLocaleDateString('fr-FR')}
                                </p>
                                <p className="text-gray-500 text-sm mb-3">
                                    💰 {e.prix > 0 ? `${e.prix} MAD` : 'Gratuit'}
                                </p>

                                <div className="flex items-center justify-between mb-4">
                                    <span className="text-sm text-gray-600">
                                        Places restantes :
                                    </span>
                                    <span className={`font-bold text-sm ${
                                        e.places_restantes > 0
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    }`}>
                                        {e.places_restantes > 0
                                            ? e.places_restantes
                                            : 'Complet'}
                                    </span>
                                </div>

                                {e.mon_inscription ? (
                                    <span className={`w-full block text-center py-2 rounded text-sm font-medium ${
                                        e.mon_inscription.statut === 'confirmee'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700'
                                    }`}>
                                        {e.mon_inscription.statut === 'confirmee'
                                            ? '✅ Inscrit'
                                            : '⏳ Liste d\'attente'}
                                    </span>
                                ) : (
                                    <button
                                        onClick={() => inscrire(e.id)}
                                        className="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 text-sm font-medium"
                                    >
                                        S'inscrire
                                    </button>
                                )}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}