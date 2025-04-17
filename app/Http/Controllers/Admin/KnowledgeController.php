<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeArticle;
use App\Models\KnowledgeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class KnowledgeController extends Controller
{
    /**
     * Display a listing of knowledge articles.
     */
    public function index(Request $request)
    {
        $query = KnowledgeArticle::with(['category', 'author']);
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by published status
        if ($request->has('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        
        // Search by title or content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Get statistics
        $totalArticles = KnowledgeArticle::count();
        $publishedArticles = KnowledgeArticle::where('is_published', true)->count();
        $draftArticles = KnowledgeArticle::where('is_published', false)->count();
        $categoriesCount = KnowledgeCategory::count();
        
        // Get popular articles
        $popularArticles = KnowledgeArticle::orderBy('views_count', 'desc')
            ->take(5)
            ->get();
        
        $articles = $query->latest()->paginate(10);
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        
        return view('admin.knowledge.index', compact(
            'articles', 
            'categories', 
            'totalArticles', 
            'publishedArticles', 
            'draftArticles', 
            'categoriesCount', 
            'popularArticles'
        ));
    }

    /**
     * Display a listing of published knowledge articles
     */
    public function published(Request $request)
    {
        $query = KnowledgeArticle::with(['category', 'author'])
            ->where('is_published', true);
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Search by title or content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Get statistics
        $totalArticles = KnowledgeArticle::count();
        $publishedArticles = KnowledgeArticle::where('is_published', true)->count();
        $draftArticles = KnowledgeArticle::where('is_published', false)->count();
        $categoriesCount = KnowledgeCategory::count();
        
        // Get popular articles
        $popularArticles = KnowledgeArticle::where('is_published', true)
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
        
        $articles = $query->latest()->paginate(10);
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        
        return view('admin.knowledge.published', compact(
            'articles', 
            'categories', 
            'totalArticles', 
            'publishedArticles', 
            'draftArticles', 
            'categoriesCount', 
            'popularArticles'
        ));
    }

    /**
     * Display a listing of draft knowledge articles
     */
    public function drafts(Request $request)
    {
        $query = KnowledgeArticle::with(['category', 'author'])
            ->where('is_published', false);
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Search by title or content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Get statistics
        $totalArticles = KnowledgeArticle::count();
        $publishedArticles = KnowledgeArticle::where('is_published', true)->count();
        $draftArticles = KnowledgeArticle::where('is_published', false)->count();
        $categoriesCount = KnowledgeCategory::count();
        
        // Get popular articles
        $popularArticles = KnowledgeArticle::orderBy('views_count', 'desc')
            ->take(5)
            ->get();
        
        $articles = $query->latest()->paginate(10);
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        
        return view('admin.knowledge.drafts', compact(
            'articles', 
            'categories', 
            'totalArticles', 
            'publishedArticles', 
            'draftArticles', 
            'categoriesCount', 
            'popularArticles'
        ));
    }

    /**
     * Display a listing of featured knowledge articles
     */
    public function featured(Request $request)
    {
        $query = KnowledgeArticle::with(['category', 'author'])
            ->where('is_featured', true);
        
        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter by published status
        if ($request->has('status')) {
            $query->where('is_published', $request->status === 'published');
        }
        
        // Search by title or content
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Get statistics
        $totalArticles = KnowledgeArticle::count();
        $publishedArticles = KnowledgeArticle::where('is_published', true)->count();
        $draftArticles = KnowledgeArticle::where('is_published', false)->count();
        $featuredArticles = KnowledgeArticle::where('is_featured', true)->count();
        $categoriesCount = KnowledgeCategory::count();
        
        $articles = $query->latest()->paginate(10);
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        
        return view('admin.knowledge.featured', compact(
            'articles', 
            'categories', 
            'totalArticles', 
            'publishedArticles', 
            'draftArticles',
            'featuredArticles',
            'categoriesCount'
        ));
    }

    /**
     * Show the form for creating a new article.
     */
    public function create()
    {
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        return view('admin.knowledge.create', compact('categories'));
    }

    /**
     * Store a newly created article in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:knowledge_categories,id',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'tags' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Generate slug from title
        $slug = Str::slug($request->title);
        
        // Check if slug exists
        $count = KnowledgeArticle::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        // Process tags
        $tags = [];
        if ($request->has('tags') && !empty($request->tags)) {
            $tagString = $request->tags;
            $tags = array_map('trim', explode(',', $tagString));
        }
        
        // Create the article
        $article = KnowledgeArticle::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'meta_description' => $request->meta_description,
            'is_published' => $request->has('is_published'),
            'author_id' => Auth::id(),
            'published_at' => $request->has('is_published') ? now() : null,
            'tags' => $tags,
        ]);
        
        return redirect()->route('admin.knowledge.articles.show', $article->id)
            ->with('success', 'Article created successfully!');
    }

    /**
     * Display the specified article.
     */
    public function show(string $id)
    {
        $article = KnowledgeArticle::with(['category', 'author'])
            ->findOrFail($id);
            
        return view('admin.knowledge.show', compact('article'));
    }

    /**
     * Show the form for editing the specified article.
     */
    public function edit(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $categories = KnowledgeCategory::orderBy('name', 'asc')->get();
        
        return view('admin.knowledge.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified article in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|exists:knowledge_categories,id',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
            'tags' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $article = KnowledgeArticle::findOrFail($id);
        
        // Only update slug if title changed
        if ($article->title !== $request->title) {
            // Generate slug from title
            $slug = Str::slug($request->title);
            
            // Check if slug exists (excluding current article)
            $count = KnowledgeArticle::where('slug', $slug)
                ->where('id', '!=', $id)
                ->count();
                
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }
            
            $article->slug = $slug;
        }
        
        // Process tags
        $tags = [];
        if ($request->has('tags') && !empty($request->tags)) {
            $tagString = $request->tags;
            $tags = array_map('trim', explode(',', $tagString));
        }
        
        // Update published_at if publishing first time
        $isNewlyPublished = !$article->is_published && $request->has('is_published');
        
        // Update the article
        $article->title = $request->title;
        $article->content = $request->content;
        $article->category_id = $request->category_id;
        $article->meta_description = $request->meta_description;
        $article->is_published = $request->has('is_published');
        $article->tags = $tags;
        
        if ($isNewlyPublished) {
            $article->published_at = now();
        }
        
        $article->save();
        
        return redirect()->route('admin.knowledge.articles.show', $article->id)
            ->with('success', 'Article updated successfully!');
    }

    /**
     * Remove the specified article from storage.
     */
    public function destroy(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $article->delete();
        
        return redirect()->route('admin.knowledge.index')
            ->with('success', 'Article deleted successfully!');
    }

    /**
     * Publish an article
     */
    public function publish(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $article->is_published = true;
        
        // Set published_at date if it's not already set
        if (!$article->published_at) {
            $article->published_at = now();
        }
        
        $article->save();
        
        return redirect()->back()
            ->with('success', 'Article published successfully!');
    }

    /**
     * Unpublish an article
     */
    public function unpublish(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $article->is_published = false;
        $article->save();
        
        return redirect()->back()
            ->with('success', 'Article unpublished successfully!');
    }

    /**
     * Mark article as featured
     */
    public function feature(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $article->is_featured = true;
        $article->save();
        
        return redirect()->back()
            ->with('success', 'Article marked as featured successfully!');
    }

    /**
     * Remove featured status from article
     */
    public function unfeature(string $id)
    {
        $article = KnowledgeArticle::findOrFail($id);
        $article->is_featured = false;
        $article->save();
        
        return redirect()->back()
            ->with('success', 'Article removed from featured successfully!');
    }

    /**
     * Generate a slug from a title
     */
    public function generateSlug(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid title'], 400);
        }
        
        $slug = Str::slug($request->title);
        
        // Check if the slug already exists
        $count = KnowledgeArticle::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return response()->json(['slug' => $slug]);
    }

    /**
     * Upload an image for the article content
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:2048', // 2MB max
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }
        
        $image = $request->file('image');
        $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
        
        // Store the image in the public/uploads/knowledge directory
        $path = $image->storeAs('uploads/knowledge', $filename, 'public');
        
        return response()->json([
            'url' => asset('storage/' . $path),
            'path' => $path
        ]);
    }
} 