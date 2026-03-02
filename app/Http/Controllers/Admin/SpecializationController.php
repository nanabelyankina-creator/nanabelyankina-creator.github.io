<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{
    public function index()
    {
        $specializations = Specialization::orderBy('name')->get();

        return view('admin.specializations.index', compact('specializations'));
    }

    public function create()
    {
        return view('admin.specializations.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Specialization::create($data);

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Специализация создана.');
    }

    public function edit(Specialization $specialization)
    {
        return view('admin.specializations.edit', compact('specialization'));
    }

    public function update(Request $request, Specialization $specialization)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $specialization->update($data);

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Специализация обновлена.');
    }

    public function destroy(Specialization $specialization)
    {
        $specialization->delete();

        return redirect()
            ->route('admin.specializations.index')
            ->with('success', 'Специализация удалена.');
    }
}