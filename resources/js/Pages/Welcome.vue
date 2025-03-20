<script setup>
import { Head, Link } from '@inertiajs/vue3';

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
        <nav class="bg-white shadow-lg">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex justify-between h-16">
                    <!-- Logo et nom -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center">
                            <span class="text-2xl font-bold text-green-600">Antipa</span>
                        </Link>
                    </div>

                    <!-- Liens de navigation -->
                    <div class="flex items-center space-x-4">
                        <Link 
                            href="/"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50"
                        >
                            Accueil
                        </Link>
                        <Link 
                            href="/about"
                            class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50"
                        >
                            À propos
                        </Link>
                        <link
                        href="/resources/js/Pages/Dashboard.vue"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50"
                        >
                            Tableau de bord
                        </link>

                    </div>

                    <!-- Boutons Connexion/Inscription -->
                    <div class="flex items-center space-x-4">
                        <Link 
                            href="/login"
                            class="px-4 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50"
                        >
                            Connexion
                        </Link>
                        <Link 
                            href="/register"
                            class="px-4 py-2 rounded-md text-sm font-medium bg-green-600 text-white hover:bg-green-700"
                        >
                            Inscription
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <h1 class="text-3xl font-bold text-gray-900">
                    Bienvenue sur Antipa
                </h1>
                <p class="mt-4 text-lg text-gray-600">
                    Découvrez nos solutions d'analyse de données.
                </p>
            </div>
        </main>
    </div>
</template>

<style>
/* Styles additionnels si nécessaire */
</style>
