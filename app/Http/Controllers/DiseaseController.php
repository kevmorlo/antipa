<?php

namespace App\Http\Controllers;

use App\Models\Disease;
use Illuminate\Http\Request;
use App\Constants\HttpStatusCodes;
use App\Constants\DiseaseStatusMessages;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Log;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     * If the diseases are successfully found, return a JSON response with the diseases.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @return Json
     * @throws \Exception
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * If the disease is successfully saved, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @return Json
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $disease = new Disease();

        $disease->country = $request->json->get('country');
        $disease->continent = $request->json->get('continent');

        try {
            $disease->save();
            return response()->json(['message' => DiseaseStatusMessages::CREATE_SUCCESS], HttpStatusCodes::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::CREATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     * If the disease is successfully found, return a JSON response with the disease.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Disease $disease
     * @return Json
     * @throws \Exception
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
     * Show the form for editing the specified resource.
     */
    public function edit(Disease $disease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * If the disease is successfully updated, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Request $request
     * @param Disease $disease
     * @return Json
     * @throws \Exception
     */
    public function update(Request $request, Disease $disease)
    {
        $disease = Disease::find($disease->id);

        try {
            $disease->totalConfirmed = $request->json->get('totalConfirmed');
            $disease->totalDeaths = $request->json->get('totalDeaths');
            $disease->totalActive = $request->json->get('totalActive');
            $disease->dateInfo = $request->json->get('dateInfo');
            $disease->diseaseId = $request->json->get('diseaseId');
            $disease->diseaseId = $request->json->get('diseaseId');

            $disease->save();

            return response()->json(['message' => DiseaseStatusMessages::UPDATE_SUCCESS], HttpStatusCodes::HTTP_OK);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => DiseaseStatusMessages::UPDATE_ERROR], HttpStatusCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     * If the disease is successfully deleted, return a JSON response with a success message.
     * If an error occurs, log the error and return a JSON response with an error message.
     * @param Disease $disease
     * @return Json
     * @throws \Exception
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
