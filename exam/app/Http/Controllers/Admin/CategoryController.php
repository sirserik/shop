<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('quizzes')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Название категории обязательно.',
            'name.unique' => 'Категория с таким названием уже существует.',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно создана.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::with(['quizzes' => function ($query) {
            $query->paginate(10); // Пагинация связанных тестов
        }])->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Название категории обязательно.',
            'name.unique' => 'Категория с таким названием уже существует.',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        if ($category->quizzes()->exists()) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Нельзя удалить категорию, пока к ней привязаны тесты.');
        }

        try {
            DB::transaction(function () use ($category) {
                $category->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Ошибка при удалении категории: ' . $e->getMessage());
        }

        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория успешно удалена.');
    }
}
