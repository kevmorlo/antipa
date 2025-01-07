<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\DiseaseStatusMessages;
use Illuminate\Support\Facades\Log;

class DiseaseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diseases",
     *     summary="Récupérer la liste des diseases",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des diseases"
     *     )
     * )
     */
    public function index()
    {
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
            return response()->json(['error' => DiseaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/diseases",
     *     summary="Créer une nouvelle disease",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Disease créée avec succès"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $disease = new Disease();

        $disease->name = $request->json->get('name');

        try {
            $disease->save();
            return response()->json(['message' => DiseaseStatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/diseases/{id}",
     *     summary="Afficher une disease spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la disease"
     *     )
     * )
     */
    public function show(Disease $disease)
    {
        $disease = Disease::find($disease->id);

        try {
            return response()->json($disease, HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/diseases/{id}",
     *     summary="Mettre à jour une disease spécifique",
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
     *             @OA\Property(property="name", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disease mise à jour avec succès"
     *     )
     * )
     */
    public function update(Request $request, Disease $disease)
    {
        $disease = Disease::find($disease->id);

        try {
            $disease->name = $request->json->get('name');

            $disease->save();

            return response()->json(['message' => DiseaseStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/diseases/{id}",
     *     summary="Supprimer une disease spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Disease supprimée avec succès"
     *     )
     * )
     */
    public function destroy(Disease $disease)
    {
        try {
            $disease = Disease::find($disease->id);
            $disease->delete();

            return response()->json(['message' => DiseaseStatusMessages::DELETE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::DELETE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}