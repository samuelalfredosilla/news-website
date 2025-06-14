<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsArticle;
use App\Models\Category;

class PublicController extends Controller
{
    public function index()
    {
        $latestNews = NewsArticle::published()->latest()->take(5)->get(); // 5 berita terbaru
        $categories = Category::all();
        return view('public.home', compact('latestNews', 'categories'));
    }

    public function newsList()
    {
        $newsArticles = NewsArticle::published()->latest()->paginate(10);
        $categories = Category::all();
        return view('public.news.index', compact('newsArticles', 'categories'));
    }

    public function newsDetail($slug)
    {
        $newsArticle = NewsArticle::published()->where('slug', $slug)->firstOrFail();
        $relatedNews = NewsArticle::published()
                                ->where('category_id', $newsArticle->category_id)
                                ->where('id', '!=', $newsArticle->id)
                                ->latest()
                                ->take(3)
                                ->get();
        $categories = Category::all();
        return view('public.news.show', compact('newsArticle', 'relatedNews', 'categories'));
    }

    public function newsByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $newsArticles = $category->newsArticles()->published()->latest()->paginate(10);
        $categories = Category::all(); // Untuk sidebar kategori
        return view('public.news.index', compact('newsArticles', 'category', 'categories'));
    }
}