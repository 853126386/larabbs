<?php

namespace App\Transformers;

use App\Models\Image;
use App\Models\Link;
use League\Fractal\TransformerAbstract;
class LinkTransformer extends TransformerAbstract{

    public function transform(Link $link)
    {
        return [
            'id' => $link->id,
            'title' => $link->title,
            'link' => $link->link,
        ];
    }

}
