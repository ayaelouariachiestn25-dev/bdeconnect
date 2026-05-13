import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { BarChart, Bar, XAxis, YAxis, Tooltip, Legend, ResponsiveContainer } from 'recharts';

export default function Dashboard({ stats, graphique }) {
    return (
        <AuthenticatedLayout>
            <Head title="Dashboard Admin" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center mb-6">
                        <h1 className="text-2xl font-bold text-gray-800">Dashboard BDE</h1>
                        <a
                            href="/admin/evenements/export-csv"
                            className="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm"
                        >
                            📥 Export CSV
                        </a>
                    </div>

                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <div className="bg-white shadow rounded-lg p-4 text-center">
                            <p className="text-3xl font-bold text-indigo-600">{stats.total_evenements}</p>
                            <p className="text-gray-500 text-sm mt-1">Événements</p>
                        </div>
                        <div className="bg-white shadow rounded-lg p-4 text-center">
                            <p className="text-3xl font-bold text-green-600">{stats.total_inscriptions}</p>
                            <p className="text-gray-500 text-sm mt-1">Confirmés</p>
                        </div>
                        <div className="bg-white shadow rounded-lg p-4 text-center">
                            <p className="text-3xl font-bold text-yellow-600">{stats.total_attente}</p>
                            <p className="text-gray-500 text-sm mt-1">En attente</p>
                        </div>
                        <div className="bg-white shadow rounded-lg p-4 text-center">
                            <p className="text-3xl font-bold text-blue-600">{stats.total_etudiants}</p>
                            <p className="text-gray-500 text-sm mt-1">Étudiants</p>
                        </div>
                    </div>

                    <div className="bg-white shadow rounded-lg p-6">
                        <h2 className="text-lg font-semibold text-gray-800 mb-4">
                            Inscriptions par événement
                        </h2>
                        <ResponsiveContainer width="100%" height={300}>
                            <BarChart data={graphique}>
                                <XAxis dataKey="titre" tick={{ fontSize: 12 }} />
                                <YAxis />
                                <Tooltip />
                                <Legend />
                                <Bar dataKey="confirmees" fill="#4F46E5" name="Confirmés" />
                                <Bar dataKey="attente" fill="#F59E0B" name="En attente" />
                                <Bar dataKey="capacite" fill="#E5E7EB" name="Capacité" />
                            </BarChart>
                        </ResponsiveContainer>
                    </div>

                    <div className="mt-6">
                        <Link href="/admin/evenements" className="text-indigo-600 hover:underline text-sm">
                            ← Retour aux événements
                        </Link>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}