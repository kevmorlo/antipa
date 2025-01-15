<?php

namespace App\Http\Controllers;

use App\Models\Localization;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\LocalizationStatusMessages;
use Illuminate\Support\Facades\Log;

class LocalizationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/localizations",
     *     summary="Récupérer la liste des localisations",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des localisations"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage des localisations."
     *     )
     * )
     */
    public function index()
    {
        try {
            $localizations = Localization::all();
            
            $data = $localizations->map(function ($localization) {
                return [
                    'Id' => $localization->id,
                    'Country' => $localization->country,
                    'Continent' => $localization->continent,
                ];
            });
        
            return response()->json($data, HttpStatusCodes::HTTP_OK);

        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/localizations",
     *     summary="Créer une nouvelle localisation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="continent", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Localisation créée avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la création de la localisation."
     *     )
     * )
     */
    public function store(Request $request)
    {
        $localization = new Localization();

        $localization->country = $request->input('country');
        $localization->continent = $request->input('continent');

        try {
            $localization->save();
            return response()->json(['message' => LocalizationStatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/localizations/{id}",
     *     summary="Afficher une localisation spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la localisation"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de l'affichage de la localisation."
     *     )
     * )
     */
    public function show(Localization $localization)
    {
        $localization = Localization::find($localization->id);

        try {
            return response()->json($localization, HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/localizations/{id}",
     *     summary="Mettre à jour une localisation spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="country", type="string"),
     *             @OA\Property(property="continent", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Localisation mise à jour avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la mise à jour de la localisation."
     *     )
     * )
     */
    public function update(Request $request, Localization $localization)
    {
        $localization = Localization::find($localization->id);

        try {
            $localization->country = $request->input('country');
            $localization->continent = $request->input('continent');

            $localization->save();

            return response()->json(['message' => LocalizationStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/localizations/{id}",
     *     summary="Supprimer une localisation spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Localisation supprimée avec succès"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Une erreur est survenue lors de la suppression de la localisation."
     *     )
     * )
     */
    public function destroy(Localization $localization)
    {
        try {
            $localization = Localization::find($localization->id);
            $localization->delete();

            return response()->json(['message' => LocalizationStatusMessages::DELETE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::DELETE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}