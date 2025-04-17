<script setup>
import { Head } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Antipa" />
    
    <div class="min-h-screen bg-gray-100">
        <!-- Barre de navigation -->
        <Navbar />

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">
                    Bienvenue sur Antipa
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Découvrez nos solutions d'analyse de données.
                </p>

                <!-- Section des diagrammes -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Bloc 1 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Propagation</h3>
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg">
                            <!-- Placeholder pour le diagramme 1 -->
                            <div class="flex items-center justify-center h-64">
                                <span class="text-gray-500">Graphique 1</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bloc 2 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Nombre de mort</h3>
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg">
                            <!-- Placeholder pour le diagramme 2 -->
                            <div class="flex items-center justify-center h-64">
                                <span class="text-gray-500">Graphique 2</span>
                            </div>
                        </div>
                    </div>

                    <!-- Bloc 3 -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h3 class="text-xl font-semibold mb-4">Statistiques Globales</h3>
                        <div class="aspect-w-16 aspect-h-9 bg-gray-100 rounded-lg">
                            <!-- Placeholder pour le diagramme 3 -->
                            <div class="flex items-center justify-center h-64">
                                <span class="text-gray-500">Graphique 3</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>