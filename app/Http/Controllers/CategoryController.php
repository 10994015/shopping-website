<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function index(){

    }
    public function store(Request $req){
        Log::info($req);
        $category = new Category();
        $category->name = $req->category;
        $category->save();

        return response()->json($category);
    }
}
