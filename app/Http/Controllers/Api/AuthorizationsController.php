<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
class AuthorizationsController extends Controller
{
    //

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
        return $this->response->array(['token'=>$user->id]);
    }

}
