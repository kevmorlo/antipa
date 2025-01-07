<?php

namespace App\Http\Controllers;

use App\Models\Reportcase;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\ReportcaseStatusMessages;
use Illuminate\Support\Facades\Log;

class ReportcaseController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reportcases",
     *     summary="Récupérer la liste des report cases",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des report cases"
     *     )
     * )
     */
    public function index()
    {
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
            return response()->json(['error' => ReportcaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/reportcases",
     *     summary="Créer un nouveau report case",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="totalConfirmed", type="integer"),
     *             @OA\Property(property="totalDeaths", type="integer"),
     *             @OA\Property(property="totalActive", type="integer"),
     *             @OA\Property(property="dateInfo", type="string", format="date"),
     *             @OA\Property(property="diseaseId", type="integer"),
     *             @OA\Property(property="localizationId", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Report case créé avec succès"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $reportcase = new Reportcase();

        $reportcase->totalConfirmed = $request->json->get('totalConfirmed');
        $reportcase->totalDeaths = $request->json->get('totalDeaths');
        $reportcase->totalActive = $request->json->get('totalActive');
        $reportcase->dateInfo = $request->json->get('dateInfo');
        $reportcase->diseaseId = $request->json->get('diseaseId');
        $reportcase->localizationId = $request->json->get('localizationId');

        try {
            $reportcase->save();
            return response()->json(['message' => ReportcaseStatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ReportcaseStatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reportcases/{id}",
     *     summary="Afficher un report case spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails du report case"
     *     )
     * )
     */
    public function show(Reportcase $reportcase)
    {
        $reportcase = Reportcase::find($reportcase->id);

        try {
            return response()->json($reportcase, HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ReportcaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/reportcases/{id}",
     *     summary="Mettre à jour un report case spécifique",
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
     *             @OA\Property(property="totalConfirmed", type="integer"),
     *             @OA\Property(property="totalDeaths", type="integer"),
     *             @OA\Property(property="totalActive", type="integer"),
     *             @OA\Property(property="dateInfo", type="string", format="date"),
     *             @OA\Property(property="diseaseId", type="integer"),
     *             @OA\Property(property="localizationId", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Report case mis à jour avec succès"
     *     )
     * )
     */
    public function update(Request $request, Reportcase $reportcase)
    {
        $reportcase = Reportcase::find($reportcase->id);

        try {
            $reportcase->totalConfirmed = $request->json->get('totalConfirmed');
            $reportcase->totalDeaths = $request->json->get('totalDeaths');
            $reportcase->totalActive = $request->json->get('totalActive');
            $reportcase->dateInfo = $request->json->get('dateInfo');
            $reportcase->diseaseId = $request->json->get('diseaseId');
            $reportcase->localizationId = $request->json->get('localizationId');

            $reportcase->save();

            return response()->json(['message' => ReportcaseStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ReportcaseStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/reportcases/{id}",
     *     summary="Supprimer un report case spécifique",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Report case supprimé avec succès"
     *     )
     * )
     */
    public function destroy(Reportcase $reportcase)
    {
        try {
            $reportcase = Reportcase::find($reportcase->id);
            $reportcase->delete();

            return response()->json(['message' => ReportcaseStatusMessages::DELETE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => ReportcaseStatusMessages::DELETE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}