<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\Request;

class BeneficiaryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->hasRole('Super-User')) {
                return $next($request);
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $beneficiaries = Beneficiary::where('is_active', true)->orderBy('name')->get();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json($beneficiaries);
        }

        return view('inventory.beneficiary.index', compact('beneficiaries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:beneficiaries,name',
        ]);

        $beneficiary = Beneficiary::create($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $beneficiary->id,
                'name' => $beneficiary->name,
            ], 201);
        }

        return redirect()->back()->with('success', 'تمت إضافة المستلم بنجاح');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:beneficiaries,name,' . $id,
        ]);

        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث المستلم بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث المستلم بنجاح');
    }

    public function destroy($id)
    {
        $beneficiary = Beneficiary::findOrFail($id);
        $beneficiary->is_active = false;
        $beneficiary->save();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المستلم بنجاح'
            ]);
        }

        return redirect()->back()->with('success', 'تم حذف المستلم بنجاح');
    }
}
