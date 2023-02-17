<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'orders' => Order::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) #RedirectResponse
    {
        return $request;
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order): RedirectResponse
    {
        //
    }
}
