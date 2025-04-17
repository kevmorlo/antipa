<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
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
                    <Link
                        href="/dashboard"
                        class="px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-green-600 hover:bg-gray-50"
                    >
                        Tableau de bord
                    </Link>
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
</template>