<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return view("admin.page.category", compact("categories"));
    }
    public function tree()
    {
        //
        // $categories = Category::roots()->ordered()->with('childrenRecursive')->paginate(10);
        // return view('admin.page.category', compact('categories'));
        $tree = Category::roots()
            ->ordered()
            ->with('childrenRecursive')
            ->paginate(10);

        return view('admin.page.category.tree', compact('tree'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.page.category.create_category', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable | exists:categories,id',
        ]);

        Category::create([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug(Str::title($request->input('name'))),
        ]);

        return redirect()->route('category_index')->with('success', 'Add a category successful');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }
    public function search(Request $request)
    {
        $keyword = $request->query('keyword'); // tuong duong voi $request->input('keyword')
        $categories = Category::search($keyword)->paginate(10);
        return view('admin.page.category', compact('categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::all();
        return view('admin.page.category.update_category', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'parent_id' => 'required',
        ]);
        $category->update([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id'),
            'slug' => Str::slug(Str::title($request->input('name'))),
        ]);
        return redirect()->route('category_edit', $category->id)->with('success', 'Update a successful category');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->route('category_index')->with('success', 'Delete a category successful');
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->paginate(10);
        return view('admin.page.category.categories_trash', compact('categories'));
    }

    public function category_restore($id)
    {
        $category = Category::onlyTrashed()->find($id);
        $category->restore();
        return redirect()->route('category_trash')->with('success', 'Restore Success');
    }
}
