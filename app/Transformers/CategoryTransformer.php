<?php

namespace App\Transformers;

use App\Models\Category;
use App\Models\Image;
use League\Fractal\TransformerAbstract;
class CategoryTransformer extends TransformerAbstract{


    public function transform(Category $category)
    {

        return [
            'id'=>$category->id,
            'name'=>$category->name,
            'description'=>$category->description,
        ];
    }

}
