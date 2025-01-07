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
     *     summary="Récupérer la liste des localizations",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des localizations"
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
     *     summary="Créer une nouvelle localization",
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
     *         description="Localization créée avec succès"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $localization = new Localization();

        $localization->country = $request->json->get('country');
        $localization->continent = $request->json->get('continent');

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
     *     summary="Afficher une localization spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la localization"
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
     *     summary="Mettre à jour une localization spécifique",
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
     *         description="Localization mise à jour avec succès"
     *     )
     * )
     */
    public function update(Request $request, Localization $localization)
    {
        $localization = Localization::find($localization->id);

        try {
            $localization->country = $request->json->get('country');
            $localization->continent = $request->json->get('continent');

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
     *     summary="Supprimer une localization spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Localization supprimée avec succès"
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