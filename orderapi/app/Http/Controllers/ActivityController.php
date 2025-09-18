<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActivityController extends Controller
{
    private $rules = [
        'description' => 'required|string|min:3|max:100',
        'hours' => 'required|numeric|min:1|max:9999999999',
        'technician_id' => 'required|numeric|min:1|max:99999999999999999999',
        'type_activity_id' => 'required|numeric|min:1|max:99999999999999999999'
    ];

    private $traductionAttributes = [
        'description' => 'descripción',
        'hours' => 'horas',
        'technician_id' => 'técnico',
        'type_activity_id' => 'tipo de actividad' 
    ];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activities = Activity::all();
        $activities->load(['technician', 'type_activity']);
        return response()->json($activities, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if(!empty($data))
        {
            return $data;
        }

        $activity = Activity::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'activity' => $activity
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        $activity->load(['technician', 'type_activity']);
        return response()->json($activity, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Activity $activity)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if(!empty($data))
        {
            return $data;
        }

        $activity->update($request->all());
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'activity' => $activity
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'activity' => $activity
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
