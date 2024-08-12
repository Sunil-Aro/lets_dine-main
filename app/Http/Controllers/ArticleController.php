<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:article_show',only: ['index']),
            new Middleware('permission:article_edit',only: ['edit']),
            new Middleware('permission:article_create',only: ['create']),
            new Middleware('permission:article_delete',only: ['destroy']),
        ];
    }


    public function index()
    {
        $articles = Article::all();
        return view('article.list',compact('articles'));
    }

    public function create(Request $request)
    {
        return view('article.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5|unique:articles,title',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $article = new Article();
        $article->title = $request->title;
        $article->description = $request->description;
        $article->username = Auth::user()->name;
        $article->email = Auth::user()->email;
        $article->save();
        return response()->json(['message' => 'Article Created successfully!'], 201);
    }

    public function edit(string $id)
    {
        $articles = Article::find($id);
        return view('article.edit',compact('articles'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|min:5|unique:articles,title,' . $request->id,
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $article = Article::find($request->id);
        $article->title = $request->title;
        $article->description = $request->description;
        $article->username = Auth::user()->name;
        $article->email = Auth::user()->email;
        $article->update();
        return response()->json(['message' => 'Article Updated successfully!'], 201);
    }

    public function destroy(string $id){
        $article = Article::find($id);
        $article->delete();
        return response()->json(['message' => 'Article Deleted successfully!'],201);
    }
}
