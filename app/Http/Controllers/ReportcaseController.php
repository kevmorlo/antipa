<?php

namespace App\Http\Controllers;

use App\Models\reportcase;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\reportcaseStatusMessages;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;

class reportcaseController extends Controller
{
    /**
     * Display a listing of the projects.
     * Return the inertia view for the projects.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @return Json
     * @throws \Exception
     */
    public function index()
        {
            try {
                $reportcases = reportcase::all();
                
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
                return response()->json(['error' => reportcaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * If the report case is successfully saved, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @return Json
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $reportcase = new reportcase();

        $reportcase->totalConfirmed = $request->json->get('totalConfirmed');
        $reportcase->totalDeaths = $request->json->get('totalDeaths');
        $reportcase->totalActive = $request->json->get('totalActive');
        $reportcase->dateInfo = $request->json->get('dateInfo');
        $reportcase->diseaseId = $request->json->get('diseaseId');
        $reportcase->localizationId = $request->json->get('localizationId');

        try {
            $reportcase->save();
            return response()->json(['message' => reportcaseStatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => reportcaseStatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     * If the report case is successfully found, return a JSON response with the report case.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param reportcase $reportcase
     * @return Json
     * @throws \Exception
     */
    public function show(reportcase $reportcase)
    {
        $reportcase = reportcase::find($reportcase->id);

        try {
            return response()->json($reportcase, HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => reportcaseStatusMessages::DISPLAY_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(reportcase $reportcase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * If the report case is successfully updated, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @param reportcase $reportcase
     * @return Json
     * @throws \Exception
     */
    public function update(Request $request, reportcase $reportcase)
    {
        $reportcase = reportcase::find($reportcase->id);

        try {
            $reportcase->totalConfirmed = $request->json->get('totalConfirmed');
            $reportcase->totalDeaths = $request->json->get('totalDeaths');
            $reportcase->totalActive = $request->json->get('totalActive');
            $reportcase->dateInfo = $request->json->get('dateInfo');
            $reportcase->diseaseId = $request->json->get('diseaseId');
            $reportcase->localizationId = $request->json->get('localizationId');

            $reportcase->save();

            return response()->json(['message' => reportcaseStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => reportcaseStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(reportcase $reportcase)
    {
        try {
            $reportcase = reportcase::find($reportcase->id);
            $reportcase->delete();

            return response()->json(['message' => reportcaseStatusMessages::DELETE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => reportcaseStatusMessages::DELETE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
