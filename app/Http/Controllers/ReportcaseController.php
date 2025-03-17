<?php

namespace App\Http\Controllers;

use App\Models\Reportcase;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\StatusMessages;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

/**
 * Ce contrôleur gère les opérations CRUD pour les cas de maladies reportés.
 * Toutes les routes sont protégées par l'authentification Sanctum avec des permissions spécifiques:
 * - ReportcaseController::index - Pour afficher les cas
 * - ReportcaseController::store - Pour créer un cas
 * - ReportcaseController::show - Pour afficher un cas spécifique
 * - ReportcaseController::update - Pour mettre à jour un cas (l'utilisateur doit également être le propriétaire)
 * - ReportcaseController::delete - Pour supprimer un cas
 */
class ReportcaseController extends Controller
{
    /**
     * Récupère la liste de tous les cas reportés.
     *
     * @OA\Get(
     *     path="/api/reportcases",
     *     tags={"Reportcases"},
     *     summary="Récupérer la liste des cas reportés",
     *     description="Récupère tous les cas de maladie reportés disponibles dans le système",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des cas reportés",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="Id", type="integer", example=1),
     *                 @OA\Property(property="TotalConfirmed", type="integer", example=100),
     *                 @OA\Property(property="TotalDeaths", type="integer", example=10),
     *                 @OA\Property(property="TotalActive", type="integer", example=90),
     *                 @OA\Property(property="DateInfo", type="string", format="date", example="2025-01-15"),
     *                 @OA\Property(property="DiseaseId", type="integer", example=1),
     *                 @OA\Property(property="LocalizationId", type="integer", example=2)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher les cas reportés."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage des cas reportés."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @return \Illuminate\Http\JsonResponse Liste des cas reportés ou message d'erreur
     */
    public function index(Request $request)
    {
        if ($request->user()->tokenCan('reportcase:view')) {
            try {
                $reportcases = Reportcase::all();
                
                $data = $reportcases->map(function ($reportcase) {
                    return [
                        'Id' => $reportcase->id,
                        'TotalConfirmed' => $reportcase->totalConfirmed,
                        'TotalDeaths' => $reportcase->totalDeaths,
                        'TotalActive' => $reportcase->totalActive,
                        'DateInfo' => $reportcase->dateInfo,
                        'DiseaseId' => $reportcase->diseaseId,
                        'LocalizationId' => $reportcase->localizationId
                    ];
                });
            
                return response()->json($data, HttpStatusCodes::HTTP_OK);
    
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
     * Crée un nouveau cas reporté.
     *
     * @OA\Post(
     *     path="/api/reportcases",
     *     tags={"Reportcases"},
     *     summary="Créer un nouveau cas reporté",
     *     description="Crée un nouveau cas de maladie reporté avec les données fournies",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données du cas reporté à créer",
     *         @OA\JsonContent(
     *             type="object",
     *             required={"totalConfirmed", "totalDeaths", "totalActive", "dateInfo", "diseaseId", "localizationId"},
     *             @OA\Property(property="totalConfirmed", type="integer", example=100, description="Nombre total de cas confirmés"),
     *             @OA\Property(property="totalDeaths", type="integer", example=10, description="Nombre total de décès"),
     *             @OA\Property(property="totalActive", type="integer", example=90, description="Nombre total de cas actifs"),
     *             @OA\Property(property="dateInfo", type="string", format="date", example="2025-01-15", description="Date de l'information"),
     *             @OA\Property(property="diseaseId", type="integer", example=1, description="ID de la maladie concernée"),
     *             @OA\Property(property="localizationId", type="integer", example=2, description="ID de la localisation géographique")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Cas reporté créé avec succès.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Le cas reporté a été créé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à créer un cas reporté."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la création du cas reporté."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les données du nouveau cas reporté
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function store(Request $request)
    {
        if ($request->user()->tokenCan('reportcase:create')) {
            $reportcase = new Reportcase();
    
            $reportcase->totalConfirmed = $request->input('totalConfirmed');
            $reportcase->totalDeaths = $request->input('totalDeaths');
            $reportcase->totalActive = $request->input('totalActive');
            $reportcase->dateInfo = $request->input('dateInfo');
            $reportcase->diseaseId = $request->input('diseaseId');
            $reportcase->localizationId = $request->input('localizationId');
    
            try {
                $reportcase->save();
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
     * Affiche un cas reporté spécifique.
     *
     * @OA\Get(
     *     path="/api/reportcases/{id}",
     *     tags={"Reportcases"},
     *     summary="Afficher un cas reporté spécifique",
     *     description="Retourne les détails d'un cas reporté spécifique",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cas reporté à afficher",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du cas reporté",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="totalConfirmed", type="integer", example=100),
     *             @OA\Property(property="totalDeaths", type="integer", example=10),
     *             @OA\Property(property="totalActive", type="integer", example=90),
     *             @OA\Property(property="dateInfo", type="string", format="date", example="2025-01-15"),
     *             @OA\Property(property="diseaseId", type="integer", example=1),
     *             @OA\Property(property="localizationId", type="integer", example=2),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à afficher un cas reporté."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage du cas reporté."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Reportcase $reportcase Instance du cas reporté à afficher (injection de modèle)
     * @return \Illuminate\Http\JsonResponse Détails du cas reporté ou message d'erreur
     */
    public function show(Request $request, Reportcase $reportcase)
    {
        if ($request->user()->tokenCan('reportcase:view')) {
            $reportcase = Reportcase::find($reportcase->id);
    
            try {
                return response()->json(
                    $reportcase,
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
     * Met à jour un cas reporté spécifique.
     *
     * @OA\Put(
     *     path="/api/reportcases/{id}",
     *     tags={"Reportcases"},
     *     summary="Mettre à jour un cas reporté spécifique",
     *     description="Met à jour les données d'un cas reporté existant",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cas reporté à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles données du cas reporté",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="totalConfirmed", type="integer", example=150),
     *             @OA\Property(property="totalDeaths", type="integer", example=15),
     *             @OA\Property(property="totalActive", type="integer", example=135),
     *             @OA\Property(property="dateInfo", type="string", format="date", example="2025-01-20"),
     *             @OA\Property(property="diseaseId", type="integer", example=1),
     *             @OA\Property(property="localizationId", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cas reporté mis à jour avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Le cas reporté a été mis à jour avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à mettre à jour le cas reporté."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la mise à jour du cas reporté."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant les nouvelles données
     * @param Reportcase $reportcase Instance du cas reporté à mettre à jour
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function update(Request $request, Reportcase $reportcase)
    {
        // Vérifier si l'utilisateur connecté peux modifier le cas reporté
        if ($request->user()->id === $reportcase->user_id && $request->user()->tokenCan('reportcase:update')) {
            $reportcase = Reportcase::find($reportcase->id);
            try {
                $reportcase->totalConfirmed = $request->input('totalConfirmed');
                $reportcase->totalDeaths = $request->input('totalDeaths');
                $reportcase->totalActive = $request->input('totalActive');
                $reportcase->dateInfo = $request->input('dateInfo');
                $reportcase->diseaseId = $request->input('diseaseId');
                $reportcase->localizationId = $request->input('localizationId');

                $reportcase->save();

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
     * Supprime un cas reporté spécifique.
     *
     * @OA\Delete(
     *     path="/api/reportcases/{id}",
     *     tags={"Reportcases"},
     *     summary="Supprimer un cas reporté spécifique",
     *     description="Supprime définitivement un cas reporté existant",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID du cas reporté à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cas reporté supprimé avec succès",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Le cas reporté a été supprimé avec succès.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé à supprimer un cas reporté."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la suppression du cas reporté."
     *     )
     * )
     *
     * @param Request $request Requête HTTP contenant le token d'authentification
     * @param Reportcase $reportcase Instance du cas reporté à supprimer
     * @return \Illuminate\Http\JsonResponse Message de succès ou d'erreur
     */
    public function destroy(Request $request, Reportcase $reportcase)
    {
        if ($request->user()->tokenCan('reportcase:delete')) {
            try {
                $reportcase = Reportcase::find($reportcase->id);
                $reportcase->delete();
    
                return response()->json(
                    ['message' => StatusMessages::DELETE_SUCCESS],
                    HttpStatusCodes::HTTP_OK);
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