import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Edit({ evenement }) {
    const [form, setForm] = useState({
        titre: evenement.titre,
        description: evenement.description || '',
        lieu: evenement.lieu || '',
        date_debut: evenement.date_debut.slice(0, 16),
        date_fin: evenement.date_fin.slice(0, 16),
        capacite_max: evenement.capacite_max,
        prix: evenement.prix,
    });

    const [errors, setErrors] = useState({});

    const handleChange = (e) => {
        setForm({ ...form, [e.target.name]: e.target.value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        router.put(`/admin/evenements/${evenement.id}`, form, {
            onError: (errs) => setErrors(errs),
        });
    };

    return (
        <AuthenticatedLayout>
            <Head title="Modifier l'événement" />
            <div className="py-12">
                <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
                    <h1 className="text-2xl font-bold text-gray-800 mb-6">
                        Modifier : {evenement.titre}
                    </h1>
                    <div className="bg-white shadow rounded-lg p-6">
                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Titre *
                                </label>
                                <input
                                    type="text"
                                    name="titre"
                                    value={form.titre}
                                    onChange={handleChange}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                />
                                {errors.titre && (
                                    <p className="text-red-500 text-xs mt-1">{errors.titre}</p>
                                )}
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Description
                                </label>
                                <textarea
                                    name="description"
                                    value={form.description}
                                    onChange={handleChange}
                                    rows={3}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Lieu
                                </label>
                                <input
                                    type="text"
                                    name="lieu"
                                    value={form.lieu}
                                    onChange={handleChange}
                                    className="w-full border rounded px-3 py-2 text-sm"
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Date début *
                                    </label>
                                    <input
                                        type="datetime-local"
                                        name="date_debut"
                                        value={form.date_debut}
                                        onChange={handleChange}
                                        className="w-full border rounded px-3 py-2 text-sm"
                                    />
                                    {errors.date_debut && (
                                        <p className="text-red-500 text-xs mt-1">{errors.date_debut}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Date fin *
                                    </label>
                                    <input
                                        type="datetime-local"
                                        name="date_fin"
                                        value={form.date_fin}
                                        onChange={handleChange}
                                        className="w-full border rounded px-3 py-2 text-sm"
                                    />
                                    {errors.date_fin && (
                                        <p className="text-red-500 text-xs mt-1">{errors.date_fin}</p>
                                    )}
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Capacité max *
                                    </label>
                                    <input
                                        type="number"
                                        name="capacite_max"
                                        value={form.capacite_max}
                                        onChange={handleChange}
                                        min="1"
                                        className="w-full border rounded px-3 py-2 text-sm"
                                    />
                                    {errors.capacite_max && (
                                        <p className="text-red-500 text-xs mt-1">{errors.capacite_max}</p>
                                    )}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-700 mb-1">
                                        Prix (MAD)
                                    </label>
                                    <input
                                        type="number"
                                        name="prix"
                                        value={form.prix}
                                        onChange={handleChange}
                                        min="0"
                                        step="0.01"
                                        className="w-full border rounded px-3 py-2 text-sm"
                                    />
                                </div>
                            </div>
                            <div className="flex gap-3 pt-2">
                                <button
                                    type="submit"
                                    className="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 text-sm font-medium"
                                >
                                    Enregistrer
                                </button>
                                
                                    href="/admin/evenements"
                                    className="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300 text-sm font-medium"
                              
                                    Annuler
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}