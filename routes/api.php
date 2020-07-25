<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api'
], function($api) {


    $api->group([
        'middleware'=>'api.throttle',
        'limit'=>config('api.rate_limits.sign.limit'),
        'expire'=>config('api.rate_limits.sign.expire')
    ],function ($api){
    // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
    });

});




