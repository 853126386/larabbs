<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
class AuthorizationsController extends Controller
{
    //第三方登入
    public function socialStore($type,SocialAuthorizationRequest $request)
    {

        if(!in_array($type,['weixin'])){
            return $this->response->errorBadRequest();
        }
        $driver=\Laravel\Socialite\Facades\Socialite::driver($type);

        try{
            if($code=$request->code){
                $response=$driver->getAccessTokenResponse($code);

                $token=Arr::get($response,'access_token');
            }else{
                $token=$request->access_token;
                $driver->setOpenId($request->weixin_openid);

            }
            $authUser=$driver->userFromToken($token);
        }catch (\Exception $e){
            return $this->response->errorUnauthorized($e->getMessage());
        }


        switch ($type){
            case 'weixin':
                $unionid=$authUser->offsetExists('unionid')?$authUser->offsetGet('unionid'):null;

                if($unionid){
                    $user=User::where('weixin_unionid',$unionid)->first();
                }else{
                    $user=User::where('weixin_openid',$authUser->getId())->first();
                }
                if(!$user){
                    $user=User::create([
                       'weixin_openid'=>$authUser->getId(),
                       'weixin_unionid'=>$unionid,
                       'name'=>$authUser->getNickname(),
                       'avatar'=>$authUser->getAvatar()
                    ]);
                }

                break;
        }
        $token=\Auth::guard('api')->fromUser($user);
        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }


    //账号密码登入
    public function store(AuthorizationRequest $request)
    {

        $username=$request->username;

        filter_var($username,FILTER_VALIDATE_EMAIL)?
            $credentials['email']=$username:
            $credentials['phone']=$username;

        $credentials['password']=$request->password;

        if(!$token=\Auth::guard('api')->attempt($credentials)){
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }

        return $this->response->array([
            'access_token'=>$token,
            'token_type'=>'Bearer',
            'expire_in'=> \Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }




    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
