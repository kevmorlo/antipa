<?php

namespace App\Http\Controllers;

use App\Models\Localization;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\LocalizationStatusMessages;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;

class LocalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     * If the localizations are successfully found, return a JSON response with the localizations.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @return Json
     * @throws \Exception
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * If the localization is successfully saved, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @return Json
     * @throws \Exception
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
     * Display the specified resource.
     * If the localization is successfully found, return a JSON response with the localization.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Localization $localization
     * @return Json
     * @throws \Exception
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
     * Show the form for editing the specified resource.
     */
    public function edit(Localization $localization)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * If the localization is successfully updated, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @param Localization $localization
     * @return Json
     * @throws \Exception
     */
    public function update(Request $request, Localization $localization)
    {
        $localization = Localization::find($localization->id);

        try {
            $localization->totalConfirmed = $request->json->get('totalConfirmed');
            $localization->totalDeaths = $request->json->get('totalDeaths');
            $localization->totalActive = $request->json->get('totalActive');
            $localization->dateInfo = $request->json->get('dateInfo');
            $localization->diseaseId = $request->json->get('diseaseId');
            $localization->localizationId = $request->json->get('localizationId');

            $localization->save();

            return response()->json(['message' => LocalizationStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => LocalizationStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     * If the localization is successfully deleted, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Localization $localization
     * @return Json
     * @throws \Exception
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
