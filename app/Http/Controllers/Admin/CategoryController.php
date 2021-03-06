<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->when(request()->q, function ($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->q . '%');
        })->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2000',
            'name'  => 'required|unique:categories'
        ]);

        //UPLOAD IMAGE
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        $category = Category::create([
            'image' => $image->hashName(),
            'name'  => $request->name,
            'slug'  => Str::slug($request->name, '-')
        ]);

        if ($category) {
            return redirect()->route('admin.category.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('admin.category.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function edit(Category $category) {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category) {
        $this->validate($request, [
            'name' => 'required|unique:categories,name,'.$category->id
        ]);

        //CEK JIKA ADA IMAGE KOSONG
        if ($request->file('image') == '') {

            //UPDATE DATA TANPA IMAGE
            $category = Category::findOrFail($category->id);
            $category->update([
                'name'  =>  $request->name,
                'slug'  =>  Str::slug($request->name, '-'),
            ]);
        } else {

            //HAPUS IMAGE LAMA
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            //UPLOAD IMAGE BARU 
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            //UPDATE DENGAN IAMGE BARU
            $category = Category::findOrFail($category->id);
            $category->update([
                'image' => $image->hashName(),
                'name'  => $request->name,
                'slug'  => Str::slug($request->name, '-'),
            ]);
        }

        if ($category) {
            return redirect()->route('admin.category.index')->with(['success' => 'Data Berhasil Diupdate!']);
        } else {
            return redirect()->route('admin.category.index')->with(['error' => 'Data Gagal Diupdate!']);
        }
    }

    public function destroy($id) {
        $category = Category::findOrFail($id);
        Storage::disk('local')->delete('public/categories/'.basename($category->image));
        $category->delete();

        if ($category) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
