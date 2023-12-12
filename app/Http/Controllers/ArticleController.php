<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DB::table('articles')->paginate(10);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $categoryId = $request->input('category_id');
        $sourceId = $request->input('source_id');

        $articles = Article::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->searchByKeyword($keyword);
            })
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->filterByDate($startDate, $endDate);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($sourceId, function ($query) use ($sourceId) {
                $query->where('source_id', $sourceId);
            })
            ->get();

        // Pass $articles to your view or return as needed
    }
}
