<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private $rules = [
        'legalization_date' => 'required|date|date_format:Y-m-d',
        'address' => 'required|string|min:3|max:50',
        'city' => 'required|string|min:3|max:80',
        'causal_id' => 'required|numeric|min:1|max:99999999999999999999',
        'observation_id' => 'max:99999999999999999999'
    ];

    private $traductionAttributes = [
        'legalization_date' => 'fecha de legalización',
        'address' => 'dirección',
        'city' => 'ciudad',
        'causal_id' => 'causal' ,
        'observation_id' => 'observación'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $orders->load(['causal', 'observation']);
        return response()->json($orders, Response::HTTP_OK);
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

        $order = Order::create($request->all());
        $response = [
            'message' => 'Registro creado exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['causal', 'observation']);
        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $data = $this->applyValidator($request, $this->rules, $this->traductionAttributes);
        if(!empty($data))
        {
            return $data;
        }

        $order->update($request->all());
        $response = [
            'message' => 'Registro actualizado exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        $response = [
            'message' => 'Registro eliminado exitosamente',
            'order' => $order
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * agrega una actividad a una orden
     */
    public function add_activity(string $order_id, string $activity_id)
    {
        $order = Order::find($order_id);
        if(!$order)
        {
            $response = [
                'errors' => 'No se encuentra la orden',
                'data' => [$order_id, $activity_id]
            ];   

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        $activity = Activity::find($activity_id);
        if(!$activity)
        {
            $response = [
                'errors' => 'No se encuentra la actividad',
                'data' => [$order_id, $activity_id]
            ];   

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        //guardar la actividad en order_activity
        $order->activities()->attach($activity->id);
        $response = [
            'message' => 'Actividad agregada exitosamente',
            'order_activity' => $order->activities
        ];   

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * retira una actividad a una orden
     */
    public function remove_activity(string $order_id, string $activity_id)
    {
        $order = Order::find($order_id);
        if(!$order)
        {
            $response = [
                'errors' => 'No se encuentra la orden',
                'data' => [$order_id, $activity_id]
            ];   

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }
        
        $activity = Activity::find($activity_id);
        if(!$activity)
        {
            $response = [
                'errors' => 'No se encuentra la actividad',
                'data' => [$order_id, $activity_id]
            ];   

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        }

        //eliminar la actividad en order_activity
        $order->activities()->detach($activity->id);
        $response = [
            'message' => 'Actividad eliminada exitosamente',
            'order_activity' => $order->activities
        ];   

        return response()->json($response, Response::HTTP_OK);
    }
}
