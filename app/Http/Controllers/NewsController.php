<?php

namespace App\Http\Controllers;

use App\Services\NewsApiService;

class NewsController extends Controller
{
    public function random(NewsApiService $newsApi)
    {
        $article = $newsApi->getRandomArticle();

        if (!$article) {
            return response()->json(['error' => 'No articles found'], 404);
        }

        return response()->json($article);
    }
}
