<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\StatusMessages;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

/**
 * Ce contrôleur gère les opérations CRUD pour les maladies.
 * Toutes les routes sont protégées par l'authentification Sanctum avec des permissions spécifiques:
 * - LocalizationController::index - Pour afficher les cas
 * - LocalizationController::store - Pour créer un cas
 * - LocalizationController::show - Pour afficher un cas spécifique
 * - LocalizationController::update - Pour mettre à jour un cas (l'utilisateur doit également être le propriétaire)
 * - LocalizationController::delete - Pour supprimer un cas
 */
class DiseaseController extends Controller
{
    /**
     * Récupère la liste de toutes les maladies.
     * 
     * @OA\Get(
     *     path="/api/diseases",
     *     tags={"Diseases"},
     *     summary="Récupérer la liste des maladies",
     *     description="Récupère toutes les maladies disponibles dans le système",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des maladies",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="Name", type="string", example="COVID-19")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher les maladies."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage des maladies."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @return \Illuminate\Http\JsonResponse Liste des maladies ou message d'erreur
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('disease:view')) {
            try {
                $diseases = Disease::all();
                
                $data = $diseases->map(function ($disease) {
                    return [
                        'Id' => $disease->id,
                        'Name' => $disease->name,
                    ];
                });
            
                return response()->json($data, HttpStatusCodes::HTTP_OK);
    
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => StatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => StatusMessages::FORBIDDEN_ERROR], HttpStatusCodes::HTTP_FORBIDDEN);
        }
    }

    /**
     * Crée une nouvelle maladie.
     *
     * @OA\Post(
     *     path="/api/diseases",
     *     tags={"Diseases"},
     *     summary="Créer une nouvelle maladie",
     *     description="Crée une nouvelle entrée de maladie avec les données fournies",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données de la maladie à créer",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Grippe H1N1", description="Nom de la maladie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Maladie créée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La maladie a été créée avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à créer une maladie."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la création de la maladie."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les données de la nouvelle maladie
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function store(Request $request)
    {
        if ($request->user()->tokenCan('disease:create')) {
            $disease = new Disease();
    
            $disease->name = $request->input('name');
    
            try {
                $disease->save();
                return response()->json(['message' => StatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => StatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => StatusMessages::FORBIDDEN_ERROR], HttpStatusCodes::HTTP_FORBIDDEN);
        }
    }

    /**
     * Affiche une maladie spécifique.
     *
     * @OA\Get(
     *     path="/api/diseases/{id}",
     *     tags={"Diseases"},
     *     summary="Afficher une maladie spécifique",
     *     description="Retourne les détails d'une maladie spécifique",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la maladie à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la maladie",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="COVID-19"),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher une maladie."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage de la maladie."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Disease $disease Instance de la maladie à afficher (injection de modèle)
     * @return \Illuminate\Http\JsonResponse Détails de la maladie ou message d'erreur
     */
    public function show(Request $request, Disease $disease)
    {
        if ($request->user()->tokenCan('disease:view')) {
            $disease = Disease::find($disease->id);
    
            try {
                return response()->json($disease, HttpStatusCodes::HTTP_OK);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => StatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => StatusMessages::FORBIDDEN_ERROR], HttpStatusCodes::HTTP_FORBIDDEN);
        }
    }

    /**
     * Met à jour une maladie spécifique.
     *
     * @OA\Put(
     *     path="/api/diseases/{id}",
     *     tags={"Diseases"},
     *     summary="Mettre à jour une maladie spécifique",
     *     description="Met à jour les données d'une maladie existante",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la maladie à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles données de la maladie",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="COVID-19 variant")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Maladie mise à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La maladie a été mise à jour avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à mettre à jour la maladie."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la mise à jour de la maladie."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les nouvelles données
     * @param Disease $disease Instance de la maladie à mettre à jour
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function update(Request $request, Disease $disease)
    {
        if ($request->user()->tokenCan('disease:update')) {
            $disease = Disease::find($disease->id);
    
            try {
                $disease->name = $request->input('name');
    
                $disease->save();
    
                return response()->json(['message' => StatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => StatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => StatusMessages::FORBIDDEN_ERROR], HttpStatusCodes::HTTP_FORBIDDEN);
        }
    }

    /**
     * Supprime une maladie spécifique.
     *
     * @OA\Delete(
     *     path="/api/diseases/{id}",
     *     tags={"Diseases"},
     *     summary="Supprimer une maladie spécifique",
     *     description="Supprime définitivement une maladie existante",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la maladie à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Maladie supprimée avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="La maladie a été supprimée avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à supprimer la maladie."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la suppression de la maladie."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Disease $disease Instance de la maladie à supprimer
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function destroy(Request $request, Disease $disease)
    {
        if ($request->user()->tokenCan('disease:delete')) {
            try {
                $disease = Disease::find($disease->id);
                $disease->delete();
    
                return response()->json(['message' => StatusMessages::DELETE_SUCCESS], HttpStatusCodes::HTTP_OK);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['error' => StatusMessages::DELETE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            return response()->json(['message' => StatusMessages::FORBIDDEN_ERROR], HttpStatusCodes::HTTP_FORBIDDEN);
        }
    }
}