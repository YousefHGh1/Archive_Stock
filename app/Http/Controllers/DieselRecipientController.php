<?php

namespace App\Http\Controllers;

use App\Models\DieselRecipient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DieselRecipientController extends Controller
{
    public function index()
    {
        $recipients = DieselRecipient::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $recipients
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:diesel_recipients,name',
        ]);

        $recipient = DieselRecipient::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'id' => $recipient->id,
            'name' => $recipient->name,
        ]);
    }

    public function show(DieselRecipient $dieselRecipient)
    {
        return response()->json($dieselRecipient);
    }

    public function update(Request $request, DieselRecipient $dieselRecipient): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:diesel_recipients,name,' . $dieselRecipient->id,
            'is_active' => 'boolean',
        ]);

        $dieselRecipient->update($validated);

        return response()->json([
            'success' => true,
            'id' => $dieselRecipient->id,
            'name' => $dieselRecipient->name,
        ]);
    }

    public function destroy(DieselRecipient $dieselRecipient): JsonResponse
    {
        $dieselRecipient->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => 'تم الحذف بنجاح'
        ]);
    }
}
