<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $categories = Category::withCount('articles')
            ->orderBy('position')->orderBy('name')
            ->get(['id', 'name', 'slug', 'description', 'color', 'is_active', 'position']);

        return Inertia::render('admin/categories/index', [
            'categories' => $categories,
            'canManage' => $request->user()->isAdmin(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $data = $this->validateData($request);
        Category::create($data);

        return back()->with('flash', 'Categoria criada.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $data = $this->validateData($request, $category->id);
        $category->update($data);

        return back()->with('flash', 'Categoria actualizada.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category->articles()->exists()) {
            return back()->withErrors(['category' => 'Não é possível eliminar: existem artigos associados. Desactive-a.']);
        }
        $category->delete();

        return back()->with('flash', 'Categoria eliminada.');
    }

    private function validateData(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:120', Rule::unique('categories', 'slug')->ignore($id)],
            'description' => ['nullable', 'string', 'max:255'],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'is_active' => ['boolean'],
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
