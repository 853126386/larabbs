<?php

namespace App\Transformers;

use App\Models\Image;
use App\Models\Reply;
use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;

class  RoleTransformer extends TransformerAbstract{


    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
        ];
    }

}
