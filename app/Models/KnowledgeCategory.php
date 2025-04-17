<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KnowledgeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'order'
    ];

    public function articles()
    {
        return $this->hasMany(KnowledgeArticle::class, 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(KnowledgeCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(KnowledgeCategory::class, 'parent_id');
    }

    public function publishedArticles()
    {
        return $this->articles()->where('is_published', true);
    }
} 