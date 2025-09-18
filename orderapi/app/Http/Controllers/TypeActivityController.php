<?php

namespace App\Http\Controllers;

use App\Models\TypeActivity;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TypeActivityController extends Controller
{
    private $rules = [
        'description' => 'required|string|min:3|max:100'
    ];

    private $traductionAttributes = [
        'description' => 'descripción'
    ];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = TypeActivity::all();
        return response()->json($types, Response::HTTP_OK);
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

        $type = TypeActivity::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'type_activity' => $type
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeActivity $typeActivity)
    {
        return response()->json($typeActivity, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeActivity $typeActivity)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if(!empty($data))
        {
            return $data;
        }

        $typeActivity->update($request->all());
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'type_activity' => $typeActivity
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeActivity $typeActivity)
    {
        $typeActivity->delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'type_activity' => $typeActivity
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
