<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeArticle;
use App\Models\KnowledgeCategory;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KnowledgeBaseController extends Controller
{
    /**
     * Display the knowledge base home page
     */
    public function home()
    {
        // Get categories with published article count
        $categories = KnowledgeCategory::withCount(['articles' => function($query) {
            $query->where('is_published', true);
        }])
        ->whereNull('parent_id')
        ->orderBy('order', 'asc')
        ->orderBy('name', 'asc')
        ->get();
        
        // Get most viewed articles
        $popularArticles = KnowledgeArticle::where('is_published', true)
            ->orderBy('views_count', 'desc')
            ->take(5)
            ->get();
            
        // Get recently published articles
        $recentArticles = KnowledgeArticle::where('is_published', true)
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get();
            
        return view('public.knowledge.landing', compact('categories', 'popularArticles', 'recentArticles'));
    }
    
    /**
     * Display a listing of articles in a category
     */
    public function category($slug)
    {
        $category = KnowledgeCategory::where('slug', $slug)->firstOrFail();
        
        // Get all child categories
        $childCategories = KnowledgeCategory::where('parent_id', $category->id)
            ->withCount(['articles' => function($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        
        // Get all published articles in this category
        $articles = KnowledgeArticle::where('category_id', $category->id)
            ->where('is_published', true)
            ->orderBy('title', 'asc')
            ->paginate(10);
            
        return view('public.knowledge.category', compact('category', 'childCategories', 'articles'));
    }
    
    /**
     * Display a specific article
     */
    public function article($categorySlug, $articleSlug)
    {
        $article = KnowledgeArticle::where('slug', $articleSlug)
            ->where('is_published', true)
            ->firstOrFail();
            
        // Increment view count
        $article->increment('views_count');
        
        // Get related articles based on category and tags
        $relatedArticles = KnowledgeArticle::where('id', '!=', $article->id)
            ->where('is_published', true)
            ->where(function($query) use ($article) {
                $query->where('category_id', $article->category_id);
                
                if (!empty($article->tags)) {
                    foreach ($article->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();
            
        return view('public.knowledge.article', compact('article', 'relatedArticles'));
    }
    
    /**
     * Display a specific article directly by slug
     */
    public function showArticle($slug)
    {
        $article = KnowledgeArticle::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
            
        // Increment view count
        $article->increment('views_count');
        
        // Get related articles based on category and tags
        $relatedArticles = KnowledgeArticle::where('id', '!=', $article->id)
            ->where('is_published', true)
            ->where(function($query) use ($article) {
                $query->where('category_id', $article->category_id);
                
                if (!empty($article->tags)) {
                    foreach ($article->tags as $tag) {
                        $query->orWhereJsonContains('tags', $tag);
                    }
                }
            })
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();
            
        return view('public.knowledge.article', compact('article', 'relatedArticles'));
    }
    
    /**
     * Search the knowledge base
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->route('knowledge.home');
        }
        
        // Search for articles matching the query
        $articles = KnowledgeArticle::where('is_published', true)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('meta_description', 'like', "%{$query}%");
            })
            ->orderBy('views_count', 'desc')
            ->paginate(10)
            ->appends(['query' => $query]);
            
        return view('public.knowledge.search', compact('articles', 'query'));
    }
    
    /**
     * Suggest articles based on ticket content during creation
     */
    public function suggestArticles(Request $request)
    {
        $title = $request->input('title', '');
        $description = $request->input('description', '');
        
        // Combine title and description for search
        $searchText = $title . ' ' . $description;
        
        // If the search text is too short, return empty result
        if (strlen($searchText) < 10) {
            return response()->json([
                'success' => true,
                'articles' => []
            ]);
        }
        
        // Extract keywords from search text
        $keywords = preg_split('/\s+/', $searchText, -1, PREG_SPLIT_NO_EMPTY);
        $keywords = array_filter($keywords, function($word) {
            return strlen($word) > 3; // Only use words longer than 3 characters
        });
        
        // If no valid keywords, return empty result
        if (empty($keywords)) {
            return response()->json([
                'success' => true,
                'articles' => []
            ]);
        }
        
        // Search for articles matching the keywords
        $articles = KnowledgeArticle::where('is_published', true)
            ->where(function($query) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $query->orWhere('title', 'like', "%{$keyword}%")
                          ->orWhere('content', 'like', "%{$keyword}%");
                }
            })
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get(['id', 'title', 'slug', 'meta_description', 'category_id']);
            
        // Format the results
        $formattedArticles = $articles->map(function($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'description' => $article->meta_description,
                'url' => route('knowledge.article', [
                    'categorySlug' => $article->category ? $article->category->slug : 'uncategorized',
                    'articleSlug' => $article->slug
                ])
            ];
        });
        
        return response()->json([
            'success' => true,
            'articles' => $formattedArticles
        ]);
    }
    
    /**
     * Link an article to a ticket
     */
    public function linkArticleToTicket(Request $request)
    {
        $ticketId = $request->input('ticket_id');
        $articleId = $request->input('article_id');
        
        $ticket = Ticket::findOrFail($ticketId);
        $article = KnowledgeArticle::findOrFail($articleId);
        
        // Check if already linked
        if (!$ticket->knowledgeArticles()->where('knowledge_article_id', $articleId)->exists()) {
            $ticket->knowledgeArticles()->attach($articleId);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Article linked to ticket successfully'
        ]);
    }
} 