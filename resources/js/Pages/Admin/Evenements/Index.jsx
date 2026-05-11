import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router, Link } from '@inertiajs/react';

export default function Index({ evenements, flash }) {
    const supprimer = (id) => {
        if (confirm('Supprimer cet événement ?')) {
            router.delete(`/admin/evenements/${id}`);
        }
    };

    return (
        <AuthenticatedLayout>
            <Head title="Admin - Événements" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-2xl font-bold text-gray-800">
                            Gestion des événements
                        </h1>
                        <Link
                            href="/admin/evenements/create"
                            className="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                        >
                            + Nouvel événement
                        </Link>
                    </div>

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

                    <div className="bg-white shadow rounded-lg overflow-hidden">
                        <table className="min-w-full divide-y divide-gray-200">
                            <thead className="bg-gray-50">
                                <tr>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacité</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Confirmés</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attente</th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200">
                                {evenements.map((e) => (
                                    <tr key={e.id}>
                                        <td className="px-6 py-4 text-sm font-medium text-gray-900">
                                            {e.titre}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-gray-500">
                                            {new Date(e.date_debut).toLocaleDateString('fr-FR')}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-gray-500">
                                            {e.capacite_max}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-green-600 font-medium">
                                            {e.nb_confirmees}
                                        </td>
                                        <td className="px-6 py-4 text-sm text-yellow-600 font-medium">
                                            {e.nb_attente}
                                        </td>
                                        <td className="px-6 py-4 text-sm flex gap-2">
                                            <Link
                                                href={`/admin/evenements/${e.id}/edit`}
                                                className="text-indigo-600 hover:underline"
                                            >
                                                Modifier
                                            </Link>
                                            <button
                                                onClick={() => supprimer(e.id)}
                                                className="text-red-600 hover:underline"
                                            >
                                                Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}