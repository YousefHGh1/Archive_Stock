<?php

namespace App\Http\Controllers;

use App\Models\DieselRecipient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DieselRecipientController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:diesel_recipient-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:diesel_recipient-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:diesel_recipient-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:diesel_recipient-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $recipients = DieselRecipient::orderBy('name')->paginate(15);
        return view('diesel_recipients.index', compact('recipients'));
    }

    public function create()
    {
        return view('diesel_recipients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:diesel_recipients,name',
        ]);

        $recipient = DieselRecipient::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json($recipient);
        }

        return redirect()->route('diesel_recipients.index')->with('success', 'تمت إضافة الجهة المستلمة بنجاح');
    }

    public function show(DieselRecipient $dieselRecipient)
    {
        return response()->json($dieselRecipient);
    }

    public function edit(DieselRecipient $dieselRecipient)
    {
        return view('diesel_recipients.edit', compact('dieselRecipient'));
    }

    public function update(Request $request, DieselRecipient $dieselRecipient)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255|unique:diesel_recipients,name,' . $dieselRecipient->id,
            'is_active' => 'boolean',
        ]);

        $dieselRecipient->update($validated);

        return redirect()->route('diesel_recipients.index')->with('success', 'تم تعديل الجهة المستلمة بنجاح');
    }

    public function destroy(DieselRecipient $dieselRecipient)
    {
        $dieselRecipient->update(['is_active' => false]);

        return redirect()->route('diesel_recipients.index')->with('success', 'تم حذف الجهة المستلمة بنجاح');
    }
}
