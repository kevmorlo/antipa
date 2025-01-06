# Antipa
Une application web pour explorer les données sur différentes maladies

## Groupe sur le projet
- Kevin LEBEAU : [kevmorlo](https://github.com/kevmorlo)
- Lucas CHEVALIER : [lucas-chevalier](https://github.com/lucas-chevalier)
- Josselin LE NAOUR : [MapAwareness](https://github.com/MapAwareness)
- Noah GRASLAND : [Fungus](https://github.com/Fungus21)

## Objectif

Antipa vise à fournir une plateforme intuitive permettant de visualiser, comparer et analyser les données de différentes maladies.  
Ce projet a été développé dans le cadre d'un exercice académique, en mettant l'accent sur la qualité du code et l'utilisation des frameworks modernes.

## Langages utilisés
- PHP
- JavaScript
- Vue

## Frameworks utilisés
- Laravel
- Tailwind
- Vue.Js

## Installation du projet

### Prérequis
- Composer (Laravel)
- Node
- Apache
- PHP version 8.2 ou plus récente
- MySQL ou MariaDB

### Installation
1. Clonez le dépôt 
```bash
git clone git@github.com:kevmorlo/antipa.git
```
2. Installez les dépendances :
```bash
composer install
npm install
```
3. Configurez l'environnement :
```bash
cp .env.example .env
```
4. Ajoutez une clé de chiffrement à votre application :
```bash
php artisan key:generate
```
5. Migrez la base de données et les Seeders : 
```bash
php artisan migrate --seed
```
6. Lancez l'application ainsi que votre environnement web:
```bash
php artisan serve
npm run dev
```
