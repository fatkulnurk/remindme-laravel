<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * Constructor for the class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_VIEW->value])->only(['index', 'show']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_CREATE->value])->only(['Store']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_UPDATE->value])->only(['update']);
        $this->middleware(['ability:' . \App\Enums\Tokens\TokenAbility::REMINDER_DELETE->value])->only(['destroy']);
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
