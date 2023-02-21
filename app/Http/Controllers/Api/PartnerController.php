<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


/*
 * Managing partners is not important here, so the focus is showing all partners
 * and mainly relaying orders data to them
 * */
class PartnerController extends Controller
{
    /**
     * Display all partners
     */
    public function index() {
        $partners = Partner::all(['id', 'name', 'description']);
        return \response()->json([
            'partners' => $partners
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
     * Store a new ly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner) {
        return Partner::with('items')->find($partner->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partner $partner): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Partner $partner): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partner $partner): RedirectResponse
    {
        //
    }
}
