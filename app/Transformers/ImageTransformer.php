<?php

namespace App\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;
class ImageTransformer extends TransformerAbstract{


    public function transform(Image $image)
    {

        return [
            'id'=>$image->id,
            'user_id'=>$image->user_id,
            'type'=>$image->type,
            'path'=>$image->path,
            'create_at'=>(string)$image->create_at,
            'update_at'=>(string)$image->update_at,
        ];

    }

}
