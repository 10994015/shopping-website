<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(){
        $categories = Category::all();
        return response()->json($categories);
    }
    public function store(Request $req){
        $category = new Category();
        $category->name = $req->category;
        $category->save();

        return response()->json($category);
    }
    public function update(Request $req){
        $category = Category::find($req->category['id']);
        $category->name = $req->category['name'];
        $category->save();

        return response()->json($category);
    }
    public function destroy(Request $req){
        $category = Category::find($req->id);
        $category->delete();

        $categories = Category::all();
        return response()->json($categories);
    }
}
