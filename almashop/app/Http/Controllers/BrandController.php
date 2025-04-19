<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = Brand::query();

        // 🔍 Поиск по имени и slug
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        // 🔄 Фильтр по активности
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // ↕️ Сортировка
        if ($sort = $request->input('sort')) {
            switch ($sort) {
                case 'name_asc':
                    $query->orderBy('name');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at');
                    break;
                case 'date_desc':
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->latest();
        }

        $brands = $query->paginate(10)->appends($request->query());

        return view('brand.index', compact('brands'));
    }



    public function create()
    {
        return view('brand.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'  => 'required|string|max:255|unique:brands,name',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        return view('brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }


    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name'  => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('image')) {
            // Удалить старое изображение
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        $brand->update($data);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Brand deleted successfully.');
    }

}
