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
     * Display a listing of the resource.
     * If the report cases are successfully found, return a JSON response with the report cases.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @return Json
     * @throws \Exception
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
        $reportcase = new Reportcase();

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
     * @param Reportcase $reportcase
     * @return Json
     * @throws \Exception
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
     * Show the form for editing the specified resource.
     */
    public function edit(Reportcase $reportcase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * If the report case is successfully updated, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @param Reportcase $reportcase
     * @return Json
     * @throws \Exception
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
     * Remove the specified resource from storage.
     * If the report case is successfully deleted, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Reportcase $reportcase
     * @return Json
     * @throws \Exception
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
