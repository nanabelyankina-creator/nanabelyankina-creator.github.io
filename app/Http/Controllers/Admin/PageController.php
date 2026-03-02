<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('slug')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'slug'    => ['required', 'string', 'max:100', 'unique:pages,slug', 'regex:/^[a-z0-9\-]+$/'],
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        Page::create($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Страница создана.');
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'slug'    => ['required', 'string', 'max:100', 'unique:pages,slug,' . $page->id, 'regex:/^[a-z0-9\-]+$/'],
            'title'   => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $page->update($data);

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Страница обновлена.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Страница удалена.');
    }
}
