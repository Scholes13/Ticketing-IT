<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class KnowledgeCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = KnowledgeCategory::withCount('articles')
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->paginate(15);
            
        return view('admin.knowledge.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = KnowledgeCategory::whereNull('parent_id')
            ->orderBy('name', 'asc')
            ->get();
            
        return view('admin.knowledge.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:knowledge_categories,id',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Generate slug from name
        $slug = Str::slug($request->name);
        
        // Check if slug exists
        $count = KnowledgeCategory::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        // Create the category
        $category = KnowledgeCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'parent_id' => $request->parent_id,
            'icon' => $request->icon,
            'order' => $request->order ?? 0,
        ]);
        
        return redirect()->route('admin.knowledge.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(string $id)
    {
        $category = KnowledgeCategory::findOrFail($id);
        $parentCategories = KnowledgeCategory::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name', 'asc')
            ->get();
            
        return view('admin.knowledge.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:knowledge_categories,id',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $category = KnowledgeCategory::findOrFail($id);
        
        // Prevent category from being its own parent
        if ($request->parent_id == $id) {
            return redirect()->back()
                ->withErrors(['parent_id' => 'A category cannot be its own parent.'])
                ->withInput();
        }
        
        // Only update slug if name changed
        if ($category->name !== $request->name) {
            // Generate slug from name
            $slug = Str::slug($request->name);
            
            // Check if slug exists (excluding current category)
            $count = KnowledgeCategory::where('slug', $slug)
                ->where('id', '!=', $id)
                ->count();
                
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            
            $category->slug = $slug;
        }
        
        // Update the category
        $category->name = $request->name;
        $category->description = $request->description;
        $category->parent_id = $request->parent_id;
        $category->icon = $request->icon;
        $category->order = $request->order ?? 0;
        $category->save();
        
        return redirect()->route('admin.knowledge.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(string $id)
    {
        $category = KnowledgeCategory::findOrFail($id);
        
        // Check if category has articles
        $articlesCount = $category->articles()->count();
        if ($articlesCount > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with articles. Please reassign or delete the articles first.');
        }
        
        // Update child categories to have null parent_id
        KnowledgeCategory::where('parent_id', $id)
            ->update(['parent_id' => null]);
        
        $category->delete();
        
        return redirect()->route('admin.knowledge.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
} 