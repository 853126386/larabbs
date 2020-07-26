<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    //

    public function store(UserRequest $request)
    {

        $verifyData=\Cache::get($request->verification_key);
        if(!$verifyData){
            return $this->response->error('验证码失效');
        }
        if(!hash_equals($verifyData['code'],$request->verification_code)){
            return $this->response->errorUnauthorized('验证码不正确');
        }

        $user=User::create([
            'name'=>$request->name,
            'password'=>bcrypt($request->password),
            'phone'=>$verifyData['phone']

        ]);

        \Cache::forget($request->verification_key);

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ])
            ->setStatusCode(201);
    }

    public function me()
    {
        return $this->response->item($this->user(),new UserTransformer());

    }
}
