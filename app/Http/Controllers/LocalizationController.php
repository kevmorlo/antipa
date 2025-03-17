<?php

namespace App\Http\Controllers;

use App\Models\Localization;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\StatusMessages;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

/**
 * Ce contrôleur gère les opérations CRUD pour les localisations géographiques.
 * Toutes les routes sont protégées par l'authentification Sanctum avec des permissions spécifiques:
 * - LocalizationController::index - Pour afficher les cas
 * - LocalizationController::store - Pour créer un cas
 * - LocalizationController::show - Pour afficher un cas spécifique
 * - LocalizationController::update - Pour mettre à jour un cas (l'utilisateur doit également être le propriétaire)
 * - LocalizationController::delete - Pour supprimer un cas
 */
class LocalizationController extends Controller
{
    /**
     * Récupère la liste de toutes les localisations.
     *
     * @OA\Get(
     *     path="/api/localizations",
     *     tags={"Localizations"},
     *     summary="Récupérer la liste des localisations",
     *     description="Récupère toutes les localisations géographiques disponibles dans le système",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des localisations",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="Country", type="string", example="France"),
     *                 @OA\Property(property="Continent", type="string", example="Europe")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher les localisations."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage des localisations."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @return \Illuminate\Http\JsonResponse Liste des localisations ou message d'erreur
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('localization:view')) {
            try {
                $localizations = Localization::all();
                
                $data = $localizations->map(function ($localization) {
                    return [
                        'Id' => $localization->id,
                        'Country' => $localization->country,
                        'Continent' => $localization->continent,
                    ];
                });
            
                return response()->json(
                    $data,
                    HttpStatusCodes::HTTP_OK
                );
    
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(
                    ['error' => StatusMessages::DISPLAY_ERROR],
                    HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return response()->json(
                ['message' => StatusMessages::FORBIDDEN_ERROR],
                HttpStatusCodes::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * Crée une nouvelle localisation.
     *
     * @OA\Post(
     *     path="/api/localizations",
     *     tags={"Localizations"},
     *     summary="Créer une nouvelle localisation",
     *     description="Crée une nouvelle entrée de localisation géographique avec les données fournies",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la localisation à créer",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"country", "continent"},
     *             @OA\Property(property="country", type="string", example="Allemagne", description="Nom du pays"),
     *             @OA\Property(property="continent", type="string", example="Europe", description="Nom du continent")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Localisation créée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La localisation a été créée avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à créer une localisation."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la création de la localisation."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les données de la nouvelle localisation
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function store(Request $request)
    {
        if ($request->user()->tokenCan('localization:create')) {
            $localization = new Localization();
    
            $localization->country = $request->input('country');
            $localization->continent = $request->input('continent');
    
            try {
                $localization->save();
                return response()->json(
                    ['message' => StatusMessages::CREATE_SUCCESS],
                    HttpStatusCodes::HTTP_CREATED
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(
                    ['error' => StatusMessages::CREATE_ERROR],
                    HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return response()->json(
                ['message' => StatusMessages::FORBIDDEN_ERROR],
                HttpStatusCodes::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * Affiche une localisation spécifique.
     *
     * @OA\Get(
     *     path="/api/localizations/{id}",
     *     tags={"Localizations"},
     *     summary="Afficher une localisation spécifique",
     *     description="Retourne les détails d'une localisation géographique spécifique",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la localisation à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la localisation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="country", type="string", example="France"),
     *             @OA\Property(property="continent", type="string", example="Europe"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher une localisation."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage de la localisation."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Localization $localization Instance de la localisation à afficher (injection de modèle)
     * @return \Illuminate\Http\JsonResponse Détails de la localisation ou message d'erreur
     */
    public function show(Request $request, Localization $localization)
    {
        if ($request->user()->tokenCan('localization:view')) {
            $localization = Localization::find($localization->id);
    
            try {
                return response()->json(
                    $localization,
                    HttpStatusCodes::HTTP_OK
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(
                    ['error' => StatusMessages::DISPLAY_ERROR],
                    HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return response()->json(
                ['message' => StatusMessages::FORBIDDEN_ERROR],
                HttpStatusCodes::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * Met à jour une localisation spécifique.
     *
     * @OA\Put(
     *     path="/api/localizations/{id}",
     *     tags={"Localizations"},
     *     summary="Mettre à jour une localisation spécifique",
     *     description="Met à jour les données d'une localisation géographique existante",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la localisation à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles données de la localisation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="country", type="string", example="Italie"),
     *             @OA\Property(property="continent", type="string", example="Europe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Localisation mise à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La localisation a été mise à jour avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à mettre à jour la localisation."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la mise à jour de la localisation."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les nouvelles données
     * @param Localization $localization Instance de la localisation à mettre à jour
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function update(Request $request, Localization $localization)
    {
        if ($request->user()->tokenCan('localization:update')) {
            $localization = Localization::find($localization->id);
    
            try {
                $localization->country = $request->input('country');
                $localization->continent = $request->input('continent');
    
                $localization->save();
    
                return response()->json(
                    ['message' => StatusMessages::UPDATE_SUCCESS],
                    HttpStatusCodes::HTTP_OK
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(
                    ['error' => StatusMessages::UPDATE_ERROR],
                    HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return response()->json(
                ['message' => StatusMessages::FORBIDDEN_ERROR],
                HttpStatusCodes::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * Supprime une localisation spécifique.
     *
     * @OA\Delete(
     *     path="/api/localizations/{id}",
     *     tags={"Localizations"},
     *     summary="Supprimer une localisation spécifique",
     *     description="Supprime définitivement une localisation géographique existante",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la localisation à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Localisation supprimée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La localisation a été supprimée avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à supprimer la localisation."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la suppression de la localisation."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Localization $localization Instance de la localisation à supprimer
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function destroy(Request $request, Localization $localization)
    {
        if ($request->user()->tokenCan('localization:delete')) {
            try {
                $localization = Localization::find($localization->id);
                $localization->delete();
    
                return response()->json(
                    ['message' => StatusMessages::DELETE_SUCCESS],
                    HttpStatusCodes::HTTP_OK
                );
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(
                    ['error' => StatusMessages::DELETE_ERROR],
                    HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        } else {
            return response()->json(
                ['message' => StatusMessages::FORBIDDEN_ERROR],
                HttpStatusCodes::HTTP_FORBIDDEN
            );
        }
    }
}