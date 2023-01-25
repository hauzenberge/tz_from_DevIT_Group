<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;

class ArticlesController extends Controller
{
    public function index()
    {
        $articles = Article::all()
        ->map(function($item){
            return [
                'id' => $item->id,
                'title' => $item->title,
                'text' => $item->text
            ];
        });
        return $articles;
    }

    public function search(Request $request)
    {
        //dd($request->input('params'));
        $sort = $request->input('params')['sort'];
        if ($sort == null ) {
            $sort = 'id';
        }
        //dd($sort);
        $search = $request->input('params')['search'];
        //dd($search);
        $articles = Article::where('id', 'like', '%'.$search.'%')
        ->orWhere('title', 'like', '%'.$search.'%')
        ->orWhere('text', 'like', '%'.$search.'%')
        ->get()
        ->sortBy($sort)
        ->map(function($item){
            return [
                'id' => $item->id,
                'title' => $item->title,
                'text' => $item->text
            ];
        });
        return $articles;
    }
}
