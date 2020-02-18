<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Topic;
class CategoriesController extends Controller
{
    //
    public function show(Request $request,Category $category)
    {
        //读取分类id关联的话题，并按每20分页
        $topics=Topic::withOrder($request->order)->where('category_id',$category->id)->paginate(10);

        //传变量话题和分类到模板

        return view('topics.index',compact('topics','category'));

    }
}
