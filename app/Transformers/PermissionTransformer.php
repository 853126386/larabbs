<?php

namespace App\Transformers;

use App\Models\Image;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

class PermissionTransformer extends TransformerAbstract{

    public function transform(Permission $pemission)
    {
        return [
            'id' => $pemission->id,
            'name' => $pemission->name,
        ];
    }

}
