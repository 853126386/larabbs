<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
], function($api) {


    $api->group([
        'middleware'=>'api.throttle',
        'limit'=>config('api.rate_limits.sign.limit'),
        'expire'=>config('api.rate_limits.sign.expire')
    ],function ($api){
    // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        //用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        //图片验证码
        $api->post('captchas','CaptchasController@store')->name('api.captchas.store');


        //第三方登入
        $api->post('socials/{socials_type}/authorizations','AuthorizationsController@socialStore')->name('api.socials.authorizations.store');


        //登入
        $api->post('authorizations','AuthorizationsController@store')->name('api.authorizations.store');


        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
    });



    $api->group([
       'middleware'=>'api.throttle',
        'limit'=>config('api.rate_limits.access.limit'),
        'expires'=>config('api.rate_limits.access.expires')
    ],function ($api){
        //游客可以访问

        //需要token才能访问
        $api->group([
            'middleware'=>'api.auth'
        ],function ($api){


            $api->get('user','UsersController@me')->name('api.user.show');
        });

    });

});




