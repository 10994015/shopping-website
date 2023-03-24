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
}
