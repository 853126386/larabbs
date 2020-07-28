<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\VerificationCodeRequest;
use Overtrue\EasySms\EasySms;
class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $requst,EasySms $easySms){
        $captchaData=\Cache::get($requst->captcha_key);
        if(!$captchaData){
            return $this->response->error('图片验证码已过期',422);
        }
        if(!hash_equals($captchaData['code'],$requst->captcha_code)){
            \Cache::forget($requst->captcha_key);
            return $this->response->errorUnauthorized('图片验证码错误');
        }

        $phone=$captchaData['phone'];
        $code=str_pad(random_int(1,9999),4,0,STR_PAD_LEFT);
        if(!app()->environment('production')){
            $code=1234;
        }else{
            try{
                $easySms->send($phone,[
                    'content'=>"验证码是{$code}"
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message=$exception->getException('aliyun')->getMessage();
                return $this->response->errorInternal($message?:'短信发送异常');
            }
        }

        $key='verificationCode_'.str_random(15);
        $expireAt=now()->addMinute(10);

        \Cache::put($key,['phone'=>$phone,'code'=>"$code"],$expireAt);

        return $this->response->array([
           'key'=>$key,
           'expire_at'=>$expireAt->toDateString()
        ])->setStatusCode(201);
    }
}
