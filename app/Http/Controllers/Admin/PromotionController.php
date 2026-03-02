<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Models\Patient;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::orderByDesc('created_at')->get();

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'content'           => ['nullable', 'string'],
            'starts_at'         => ['nullable', 'date'],
            'ends_at'           => ['nullable', 'date'],
            'is_active'         => ['sometimes', 'boolean'],
            'discount_percent'  => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция создана.');
    }

    public function edit(Promotion $promotion)
    {
        $promotion->load('patients');

        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'title'             => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'content'           => ['nullable', 'string'],
            'starts_at'         => ['nullable', 'date'],
            'ends_at'           => ['nullable', 'date'],
            'is_active'         => ['sometimes', 'boolean'],
            'discount_percent'  => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Акция обновлена.');
    }

    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Акция удалена.');
    }

    public function searchPatients(Request $request, Promotion $promotion)
    {
        $term = trim($request->query('q', ''));

        $patients = Patient::query()
            ->when($term, function ($q) use ($term) {
                $like = '%' . mb_strtolower($term) . '%';
                $q->whereRaw('LOWER(last_name) LIKE ?', [$like])
                ->orWhereRaw('LOWER(first_name) LIKE ?', [$like])
                ->orWhereRaw('LOWER(middle_name) LIKE ?', [$like])
                ->orWhereRaw('LOWER(snils) LIKE ?', [$like]);
            })
            ->orderBy('last_name')
            ->limit(20)
            ->get();

        return response()->json(
            $patients->map(function ($p) {
                return [
                    'id'    => $p->id,
                    'name'  => trim($p->last_name . ' ' . $p->first_name . ' ' . ($p->middle_name ?? '')),
                    'snils' => $p->snils,
                ];
            })
        );
    }

    public function attachPatient(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
        ]);

        $promotion->patients()->syncWithoutDetaching([$data['patient_id']]);

        return back()->with('success', 'Пациент добавлен к акции.');
    }

    public function detachPatient(Promotion $promotion, Patient $patient)
    {
        $promotion->patients()->detach($patient->id);

        return back()->with('success', 'Пациент удалён из акции.');
    }
}