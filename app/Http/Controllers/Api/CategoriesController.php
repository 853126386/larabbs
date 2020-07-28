<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    //
    public function index(Category $category)
    {
        return $this->response->collection($category::all(),new CategoryTransformer());
    }
}
